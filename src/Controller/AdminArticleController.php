<?php
namespace src\Controller;

use src\Model\Article;

class AdminArticleController extends AbstractController{

    public function list(){
        var_dump("test");
        $articles = Article::SqlGetAll();

        return "<html><h1>test</h1></html>";
    }
}