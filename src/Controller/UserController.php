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

    public static function haveGoodRole(array $rolesCompatibles) {
        if(!isset($_SESSION["login"])){
            throw new \Exception("Vous devez vous authentifier pour accéder à cette page");
        }
        // Comparaison role par role
        $roleFound = false;
        foreach ($_SESSION["login"]["Roles"] as $role){
            if(in_array($role, $rolesCompatibles)){
                $roleFound = true;
                break;
            }
        }
        if(!$roleFound){
            throw new \Exception("Vous n'avez pas le bon role pour accéder à cette page");
        }
    }

    public function logout(){
        unset($_SESSION["login"]);
        header("Location:/");
    }


}