<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'characters' => $this->characterRepository->findBy([], ['id' => 'ASC'], 20)
        ]);
    }

    /**
     * @Route("/load-more/{offset}")
     * @param int $offset
     * @return JsonResponse
     */
    public function loadMore(int $offset): JsonResponse
    {
        $characters = $this->characterRepository->findBy([], null, 20, $offset);

        $response = [];
        foreach ($characters as $character) {
            $response[] = [
                'firstName' => $character->getFirstName(),
                'lastName' => $character->getLastName(),
                'house' => $character->getHouse(),
                'picture' => $character->getPicture(),
            ];
        }

        return new JsonResponse($response);
    }
}