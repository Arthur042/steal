<?php

namespace App\Controller\front;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
        private CommentRepository $commentRepository
    ) { }

    #[Route('/game/{slug}', name: 'app_detail')]
    public function index(
        string $slug,
        Request $request,
        PaginatorInterface $paginator,
        CommentRepository $commentRepository
    ): Response
    {
        $comments = $paginator->paginate(
            $commentRepository->findByGame($slug),
            $request->query->getInt('page', 1),
            6
        );
        $comments->setParam('_fragment', 'comments');

        // Use the slug to find the game in the database
            $game = $this->gameRepository->findOneBySlug($slug);
        // Take number of comment and avg rating of the game
            $commentData = $this->commentRepository->countCommentByGame($game);
            if(count($commentData) !== 0) {
                $commentData[0]['avgRating'] = round($commentData[0]['avgRating'],2);
            }

        // load 3 games with the same genre
            $gamesSameGenre = $this->gameRepository->findThreeByGenre($game[0]->getGenres()[0]->getName());

        return $this->render('front/game_detail/index.html.twig', [
            'game' => $game,
            'commentData' => $commentData,
            'comments' => $comments,
            'gamesSameGenre' => $gamesSameGenre,
        ]);
    }
}
