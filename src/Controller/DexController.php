<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @Route("/load-more/{offset}")
     * @param int $offset
     * @return JsonResponse
     */
    public function loadMore(int $offset): JsonResponse
    {
        $characters = $this->characterRepository->findBy([], null, 12, $offset);

        $response = [];
        foreach ($characters as $character) {
            $response[] = [
                'id' => $character->getId(),
                'firstName' => $character->getFirstName(),
                'lastName' => $character->getLastName(),
                'house' => $character->getHouse(),
                'picture' => $character->getPicture(),
            ];
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/character/{id}", name="dex_details")
     * @param Character $character
     * @return Response
     */
    public function details(Character $character): Response
    {
        return $this->render('dex/details.html.twig', [
            'character' => $character
        ]);
    }
}