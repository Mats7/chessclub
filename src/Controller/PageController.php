<?php

namespace App\Controller;

use App\Config\Routes\Routes\Routes;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    public function homepage(): Response
    {

        return $this->render('homepage/home.html.twig', [
        ]);
    } 
}