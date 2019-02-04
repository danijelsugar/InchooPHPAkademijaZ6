<?php

class Comment
{

    private $id;
    private $postId;
    private $content;
    private $commentTime;

    public function __construct($id,$postId,$content,$commentTime)
    {
        $this->setId($id);
        $this->setPostId($postId);
        $this->setContent($content);
        $this->setCommentTime($commentTime);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __call($name, $arguments)
    {
        $function = substr($name, 0, 3);
        if ($function === 'set') {
            $this->__set(strtolower(substr($name, 3)), $arguments[0]);
            return $this;
        } else if ($function === 'get') {
            return $this->__get(strtolower(substr($name, 3)));
        }

        return $this;
    }

    public static function all($id)
    {
        $id = intval($id);
        $commentsList = [];
        $db = Db::connect();
        $statement = $db->prepare('select * from comment where postid=:postid order by commenttime desc');
        $statement->bindValue(':postid', $id);
        $statement->execute();
        foreach ($statement->fetchAll() as $comment) {
            $commentsList[] = new Comment($comment->id, $comment->postid, $comment->content, $comment->commenttime);
        }
        return $commentsList;
    }
}