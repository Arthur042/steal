<?php

namespace App\Controller\front;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameListController extends AbstractController
{
    public function __construct(
        GameRepository $gameRepository
    )
    {
        $this->gameRepository = $gameRepository;
    }

    #[Route('/all_games', name: 'app_game_list')]
    public function index(): Response
    {
        // query for a list of games order by $publishedAt
            $games = $this->gameRepository->findBy( [], ['publishedAt' => 'ASC'] );

        return $this->render('front/game_list/index.html.twig', [
            'games' => $games,
        ]);
    }
}
