<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/qui-sommes-nous', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }
}