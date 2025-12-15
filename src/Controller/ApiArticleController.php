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

        // Traitement image
        $sqlRepository = null;
        $nomImage = null;
        if(isset($data->Image)){
            $nomImage = uniqid().'.jpg';
            $dateNow = new \DateTime();
            $sqlRepository = $dateNow->format('Y/m');
            $repository = "{$_SERVER['DOCUMENT_ROOT']}/uploads/images/{$sqlRepository}/{$nomImage}";
            if(!is_dir($repository)){
                mkdir($repository,0777, true);
            }
            $file = fopen("{$repository}/{$nomImage}", "wb");
            fwrite($file, base64_decode($data->Image));
            fclose($file);
        }
        // Création de l'article + insert en BDD
        $article = new Article();
        $article->setTitre($data->Titre)
            ->setAuteur($data->Auteur)
            ->setDescription($data->Description)
            ->setImageRepository($sqlRepository)
            ->setImageFileName($nomImage)
            ->setDatePublication(new \DateTime($data->DatePublication));
        $id = Article::SqlAdd($article);
        return json_encode([
            'success' => true,
            'id' => $id,
            'message' => 'Article ajouté avec succès'
        ]);
    }
}