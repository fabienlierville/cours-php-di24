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


$article = new src\Model\Article();
$article->setTitre("Mon premier article")
    ->setDescription("tatata")
    ->setDatePublication(new \DateTime());
var_dump($article);