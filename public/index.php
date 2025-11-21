<?php
require __DIR__ . '/../vendor/autoload.php';
//Auto Require
function chargerclasse($classe){
    $ds = DIRECTORY_SEPARATOR;
    $dir = $_SERVER['DOCUMENT_ROOT']."$ds..";
    $className = str_replace("\\", $ds, $classe);
    $fullPath = "{$dir}{$ds}{$className}.php";
    if(is_readable($fullPath)){
        require_once $fullPath;
    }
}
spl_autoload_register("chargerclasse");

// Routeur
// ?controller=AdminArticle&action=list
$url = explode("/",$_GET['url']);
$controller = (isset($url[0])) ? $url[0] : "";
$action = (isset($url[1])) ? $url[1] : "";
$param = (isset($url[2])) ? $url[2] : "";

if($controller != ""){
    try{
        $class = "src\Controller\\{$controller}Controller";
        if(class_exists($class)){
            $controllerObj = new $class();
            if(method_exists($controllerObj, $action)){
                echo $controllerObj->$action($param);
            }else{
                throw new Exception("La méthode {$action} n'existe pas");
            }
        }else{
            throw new Exception("Le controller {$controller} n'existe pas");
        }
    }catch (Exception $e){
        $controller = new \src\Controller\ErrorController();
        echo $controller->show($e);
    }
}else{
    // Route par défaut (plus tard)
    $ctrl = new src\Controller\ArticleController();
    echo $ctrl->index();
}
