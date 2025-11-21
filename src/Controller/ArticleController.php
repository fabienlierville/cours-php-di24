<?php
namespace src\Controller;
class ArticleController extends AbstractController
{
    public function index()
    {
        //1 Récuépérer les 20 derniers articles en base

        //2 Les affiche dans la vue d'accueil
        return $this->twig->render("article/index.html.twig");

    }

}