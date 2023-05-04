<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dex")
 */
class DexController extends AbstractController
{
    private $characterRepository;

    public function __construct(CharacterRepository $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    /**
     * @Route("/", name="dex_show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('dex/index.html.twig', [
            'characters' => $this->characterRepository->findBy([], ['id' => 'ASC'], 12)
        ]);
    }
}