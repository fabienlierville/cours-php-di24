<?php
namespace src\Controller;

use src\Model\Article;

class AdminArticleController{

    public function list(){
        $articles = Article::SqlGetAll();
        var_dump($articles);
    }
}