<?php

namespace Sw\Bundle\SoapCommentBundle\Service;

use Sw\Bundle\SoapCommentBundle\Entity\Comment;

class CommentManager
{
    private $xml;
    private $path;

    public function __construct($rootDir)
    {
        $this->path = $rootDir. '/data/comments.xml';
        $this->xml = simplexml_load_file($rootDir. '/data/comments.xml');
    }

    public function addComment($swimmingPoolId, $author, $content, $rank)
    {
        $comment = $this->xml->addChild('comment');
        $comment->addChild('rank', (int) $rank);
        $comment->addChild('author', $author);
        $comment->addChild('swimmingPoolId', (int) $swimmingPoolId);
        $comment->addChild('content', $content);

        $this->xml->asXML($this->path);
    }

    public function getComments($swimmingPoolId)
    {
        $collection = array();

        $query = "/comments/comment[swimmingPoolId=".$swimmingPoolId."]";

        // http://www.php.net/manual/fr/book.simplexml.php#105330
        $comments = json_decode(json_encode($this->xml->xpath($query)), true);

        foreach ($comments as $comment) {
            $item = new Comment();
            $item
                ->setId(uniqid())
                ->setRank($comment['rank'])
                ->setAuthor($comment['author'])
                ->setSwimmingPoolId($comment['swimmingPoolId'])
                ->setContent($comment['content'])
            ;
            $collection[] = $item;
        }

        return $collection;
    }
}
