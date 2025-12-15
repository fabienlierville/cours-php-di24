<?php
namespace src\Controller;

use src\Model\Article;

class ApiArticleController{

    public function __construct(){
        header("Content-type: application/json; charset=utf-8");
    }

    public function getAll(){
        if($_SERVER['REQUEST_METHOD'] != 'GET'){
            header("HTTP/1.1 405 Method Not Allowed");
            return json_encode([
               'success' => false,
               'message' => 'Method Not Allowed'
            ]);
        }
        $articles = Article::SqlGetAll();
        return json_encode($articles);

    }
}