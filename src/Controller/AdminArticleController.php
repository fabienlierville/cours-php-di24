<?php
namespace src\Controller;

use src\Model\Article;
use src\Model\BDD;

class AdminArticleController extends AbstractController{

    public function list(){
        $articles = Article::SqlGetAll();
        return $this->twig->render(
            'admin/article/list.html.twig',
            [
                'articles' => $articles,
                "prenom" => "Vivien"
            ]
        );
    }

    public function add(){
        if(isset($_POST["Titre"])){
            //1. Upload Fichier
            $sqlRepository = null; // On ne fera pas X requetes SQL différentes donc on déclare les variables dès le début pour les utiliser dans la requete SQL
            $nomImage = null;

            if(!empty($_FILES['Image']['name']) ) {
                //Type MIME
                $fileMimeType = mime_content_type($_FILES['Image']['tmp_name']);
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                //Extension
                $extension = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];
                // strtolower = on compare ce qui est comparage (JPEG =! jpeg)
                if (in_array(strtolower($extension), $allowedExtensions) && in_array($fileMimeType, $allowedMimeTypes)) {
                    // Fabrication du répertoire d'accueil façon "Wordpress" (YYYY/MM)
                    $dateNow = new \DateTime();
                    $sqlRepository = $dateNow->format('Y/m');
                    $repository = './uploads/images/' . $dateNow->format('Y/m');
                    if (!is_dir($repository)) {
                        mkdir($repository, 0777, true);
                    }
                    // Renommage du fichier (d'où l'intéret d'avoir isolé l'extension
                    $nomImage = md5(uniqid()) . '.' . $extension;

                    //Upload du fichier, voilà c'est fini !
                    move_uploaded_file($_FILES['Image']['tmp_name'], $repository . '/' . $nomImage);
                }
            }


            //Créer un objet Article
            $article = new Article();
            $article->setTitre($_POST['Titre']);
            $article->setDescription($_POST['Description']);
            $article->setAuteur($_POST['Auteur']);
            $article->setDatePublication(new \DateTime($_POST['DatePublication']));
            $article->setImageFileName($nomImage);
            $article->setImageRepository($sqlRepository);

            //Exécuter la requete SQL d'ajout (model)
            $id = Article::SqlAdd($article);

            //Rédiriger l'internaute sur la page liste
            header("location:/AdminArticle/show/{$id}");

        }
        return $this->twig->render('admin/article/add.html.twig');
    }

    public function edit(int $id)
    {
        $article = Article::SqlGetById($id);
        if(isset($_POST["Titre"])){
            //1. Upload Fichier
            $sqlRepository = ($article->getImageRepository() != "") ? $article->getImageRepository() : null;
            $nomImage = ($article->getImageFileName() != "") ? $article->getImageFileName() : null;

            if(!empty($_FILES['Image']['name']) ) {
                //Type MIME
                $fileMimeType = mime_content_type($_FILES['Image']['tmp_name']);
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                //Extension
                $extension = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];
                // strtolower = on compare ce qui est comparage (JPEG =! jpeg)
                if (in_array(strtolower($extension), $allowedExtensions) && in_array($fileMimeType, $allowedMimeTypes)) {
                    //Si image déjà existante alors on supprime
                    if($sqlRepository!=null and $nomImage!=null){
                        unlink('./uploads/images/'.$sqlRepository.'/'.$nomImage);
                    }

                    // Fabrication du répertoire d'accueil façon "Wordpress" (YYYY/MM)
                    $dateNow = new \DateTime();
                    $sqlRepository = $dateNow->format('Y/m');
                    $repository = './uploads/images/' . $dateNow->format('Y/m');
                    if (!is_dir($repository)) {
                        mkdir($repository, 0777, true);
                    }
                    // Renommage du fichier (d'où l'intéret d'avoir isolé l'extension
                    $nomImage = md5(uniqid()) . '.' . $extension;

                    //Upload du fichier, voilà c'est fini !
                    move_uploaded_file($_FILES['Image']['tmp_name'], $repository . '/' . $nomImage);
                }
            }


            //Créer un objet Article
            $article->setTitre($_POST['Titre']);
            $article->setDescription($_POST['Description']);
            $article->setAuteur($_POST['Auteur']);
            $article->setDatePublication(new \DateTime($_POST['DatePublication']));
            $article->setImageFileName($nomImage);
            $article->setImageRepository($sqlRepository);

            //Exécuter la requete SQL d'ajout (model)
            Article::SqlUpdate($article);

            //Rédiriger l'internaute sur la page liste
            header("location:/AdminArticle/edit/{$id}");

        }
        return $this->twig->render('admin/article/edit.html.twig', [
            'article' => $article,
        ]);
    }

    public function fixtures(){
        $requete = BDD::getInstance()->prepare("TRUNCATE TABLE articles")->execute();
        $arrayAuteur = ["Enzo","Camille","Bastien","Fael","Denis"];
        $arrayTitre = ["PHP En force", "React JS une valeur sure", "C# toujours au top", "Java en baisse"];
        $dateAjout = new \DateTime();

        for($i=1;$i<=200;$i++) {
            $dateAjout->modify("+1 day");
            shuffle($arrayAuteur);
            shuffle($arrayTitre);
            $article = new Article();
            $article->setTitre($arrayTitre[0])
                ->setDescription("Zypher est un langage de programmation moderne conçu pour offrir une expérience de développement puissante et flexible. Avec une syntaxe claire et concise, Zypher permet aux développeurs de créer des applications robustes et efficaces dans divers domaines, allant de l'informatique embarquée à la programmation web")
                ->setAuteur($arrayAuteur[0])
                ->setDatePublication($dateAjout);
            Article::SqlAdd($article);
        }
        header('location: /AdminArticle/list ');
    }

    public function show($id){
        $article = Article::SqlGetById($id);
        if($article == null){
            header("location: /AdminArticle/list");
        }
        return $this->twig->render("admin/article/show.html.twig",
        [
            "article" => $article,
        ]);
    }


    public function delete($id)
    {
        $article = Article::SqlGetById($id);
        $sqlRepository = ($article->getImageRepository() != "") ? $article->getImageRepository() : null;
        $nomImage = ($article->getImageFileName() != "") ? $article->getImageFileName() : null;
        if($sqlRepository!=null and $nomImage!=null){
            unlink('./uploads/images/'.$sqlRepository.'/'.$nomImage);
        }

        Article::SqlDelete($id);
        header("location: /AdminArticle/list");
    }

}