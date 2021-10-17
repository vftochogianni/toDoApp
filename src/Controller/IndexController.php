<?php

namespace ToDoApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig');
    }
}
