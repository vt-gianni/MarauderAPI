<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index_show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('index.html.twig', []);
    }

    /**
     * @Route("/documentation", name="index_doc")
     * @return Response
     */
    public function documentation(): Response
    {
        return $this->render('documentation.html.twig', []);
    }

    /**
     * @Route("/api", name="api_show")
     * @return Response
     */
    public function api(): Response
    {
        return $this->redirectToRoute('index_doc');
    }
}