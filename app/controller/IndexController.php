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

