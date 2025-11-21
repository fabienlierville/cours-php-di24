<?php
namespace src\Controller;
use src\Model\Article;

class ArticleController extends AbstractController
{
    public function index()
    {
        //1 Récuépérer les 20 derniers articles en base
        $articles = Article::SqlGetLast(20);
        //2 Les affiche dans la vue d'accueil
        return $this->twig->render("article/index.html.twig",[
            "articles" => $articles
        ]);

    }

    public function show($id){
        $article = Article::SqlGetById($id);
        return $this->twig->render("article/show.html.twig",[
            "article" => $article
        ]);
    }

    public function search()
    {
        if(isset($_POST['Search']))
        {
            $articles = Article::SqlSearch($_POST['Search']);
            return $this->twig->render("article/search.html.twig",[
                "articles" => $articles,
                "keyword" => $_POST['Search']
            ]);

        }
        header("location: /");
    }


}