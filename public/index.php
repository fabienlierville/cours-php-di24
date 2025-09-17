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

$controller = new src\Controller\AdminArticleController();
$controller->list();