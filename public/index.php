<?php

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
$controller = (isset($_GET['controller'])) ? $_GET['controller'] : "";
$action = (isset($_GET['action'])) ? $_GET['action'] : "";
$param = (isset($_GET['param'])) ? $_GET['param'] : "";

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
        //Plus tard on gèrera les exceptions
    }
}else{
    // Route par défaut (plus tard)
    $ctrl = new src\Controller\ArticleController();
    echo $ctrl->index();
}
