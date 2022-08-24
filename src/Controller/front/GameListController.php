<?php

namespace App\Controller\front;

use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Repository\PublisherRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/all_games')]
class GameListController extends AbstractController
{
    public function __construct(
        GameRepository $gameRepository
    )
    {
        $this->gameRepository = $gameRepository;
    }

    #[Route('/', name: 'app_game_list')]
    public function index(
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $games = $paginator->paginate(
            $this->gameRepository->getQbAll(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('front/game_list/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/search/{search}', name: 'app_game_list_search')]
    public function search(
        string $search,
        GameRepository $gameRepository,
    ): JsonResponse
    {
        // decode json $search
        $search = json_decode($search, true);

        // Request in the database
        $games = $gameRepository->findByNameLike($search);

        return (new JsonResponse())->setData([
            'html' => $this->renderView('front/partial/game_search_list.html.twig', [
                'games' => $games,
                'title' => $search,
            ]),
        ]);
    }
}
