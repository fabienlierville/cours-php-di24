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

    public function add(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            header("HTTP/1.1 405 Method Not Allowed");
            return json_encode([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
        }

        //Récupération des données du Body
        $data = json_decode(file_get_contents("php://input"));

        if(empty($data->Titre) || empty($data->Auteur) || empty($data->Description)){
            header("HTTP/1.1 400 Bad Request");
            return json_encode([
                'success' => false,
                'message' => 'Il manque des données obligatoires'
            ]);
        }
        // Création de l'article + insert en BDD
        $article = new Article();
        $article->setTitre($data->Titre)
            ->setAuteur($data->Auteur)
            ->setDescription($data->Description)
            ->setDatePublication(new \DateTime($data->DatePublication));
        $id = Article::SqlAdd($article);
        return json_encode([
            'success' => true,
            'id' => $id,
            'message' => 'Article ajouté avec succès'
        ]);
    }
}