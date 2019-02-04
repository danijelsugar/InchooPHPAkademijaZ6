<?php

class IndexController
{
    public function index($id = 0)
    {
        $view = new View();
        $posts = Post::all();

        $view->render('index', [
            "posts" => $posts,
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
        $targetFile = null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = BP . 'uploads/';
            $name = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $name;
            $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);
            $allowedFileTypes=array("jpg", "jpeg");

            if (!in_array($fileType, $allowedFileTypes)) {
                echo 'Not allowed file type, only jpeg and jpg are allowed';
            }

            if ($_FILES['image']['size'] > 2097152) {
                echo 'File size too big';
            }

            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        }


        if ($data === false) {
            header('Location: ' . App::config('url'));
        } else {
            $connection = Db::connect();
            $sql = 'INSERT INTO post (content,image) VALUES (:content,:image)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue('content', $data['content']);
            $stmt->bindValue(':image', $name);

            $stmt->execute();
            header('Location: ' . App::config('url'));

        }
    }

    public function newComment()
    {
        $data = $this->_validate($_POST);

        if ($data === false) {
            header('Location: ' . App::config('url'));
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

