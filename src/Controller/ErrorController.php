<?php
namespace src\Controller;

class ErrorController extends AbstractController
{
    public function show(\Exception $exception)
    {
        return $this->twig->render("error.html.twig",
            ["message" => $exception->getMessage()]
        );
    }

}