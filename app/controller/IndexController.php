<?php

class IndexController
{
    public function index()
    {
        $view = new View();
        $posts = Post::all();

        $view->render('index', [
            "posts" => $posts
        ]);
    }

    public function view($id = 0)
    {
        $view = new View();
        $comments = Comment::all($id);

        $view->render('view', [
            "post" => Post::find($id),
            "comments" => $comments
        ]);
    }

    public function newPost()
    {
        $data = $this->_validate($_POST);

        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content,image) VALUES (:content,:image)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            if ($image=='') {
                $stmt->bindValue(':image', null, PDO::PARAM_STR);
            } else {
                $stmt->bindParam(':image', $image);
            }

            $stmt->execute();
            header('Location: ' . App::config('url'));
        }
    }

    public function newComment()
    {
        $data = $this->_validate($_POST);

        if ($data === false) {

        } else {
            $connection = Db::connect();
            $sql = 'insert into comment (postid, content) values (:postid, :content)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':postid', $data['postid']);
            $stmt->bindValue(':content', $data['content']);

            $stmt->execute();
            header('Location: ' . App::config('url') . 'Index/view/' . $data['postid']);
        }
    }

    public function deletePost($id)
    {


        $connection = Db::connect();

        $sql = 'delete from comment where postid=:postid';
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':postid', $id);
        $stmt->execute();


        $sql = 'delete from post where id=:id';
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();


        header('Location: ' . App::config('url'));
    }

    /**
     * @param $data
     * @return array|bool
     */
    private function _validate($data)
    {
        $required = ['content'];

        //validate required keys
        foreach ($required as $key) {
            if (!isset($data[$key])) {
                return false;
            }

            $data[$key] = trim((string)$data[$key]);
            if (empty($data[$key])) {
                return false;
            }
        }
        return $data;
    }





}

