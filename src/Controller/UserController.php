<?php
namespace src\Controller;

use src\Model\User;

class UserController extends AbstractController
{
    public function login(){
        if(isset($_POST["mail"]) && isset($_POST["password"])){
            //Requete SQL qui va cherches les info du User avec le mail
            $user = User::SqlGetByMail($_POST["mail"]);
            if($user!=null){
                //Comparer le mdp hasché avec celui saisi dans le formulaire
                if(password_verify($_POST["password"], $user->getPassword())){
                    //Créer les sessions sinon Lever une Exception
                    // Et rediriger vers /AdminArticle/list
                    $_SESSION["login"] = [
                        "Email" => $user->getEmail(),
                        "Roles" => $user->getRoles()
                    ];
                    header("Location:/AdminArticle/list");
                }else{
                    throw new \Exception("Mot de passe incorrect pour {$_POST["mail"]}");
                }
            }else{
                throw new \Exception("Aucun user avec ce mail en base");
            }
        }

        return $this->twig->render("user/login.html.twig");
    }

}