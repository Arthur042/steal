<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository
    ) { }

    #[Route('/game/{slug}', name: 'app_detail')]
    public function index(string $slug): Response
    {
        // Use the slug to find the game in the database
            $game = $this->gameRepository->findOneBySlug($slug);

        return $this->render('game_detail/index.html.twig', [
            'game' => $game,
        ]);
    }
}
