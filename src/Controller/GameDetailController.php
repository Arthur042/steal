<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
        private CommentRepository $commentRepository
    ) { }

    #[Route('/game/{slug}', name: 'app_detail')]
    public function index(string $slug): Response
    {
        // Use the slug to find the game in the database
            $game = $this->gameRepository->findOneBySlug($slug);
        // Take number of comment and avg rating of the game
            $commentData = $this->commentRepository->countCommentByGame($game);
            $commentData[0]['avgRating'] = round($commentData[0]['avgRating'],2);
        // load star icons according to the avg rating
            $rating = $commentData[0]['avgRating'];
        // load comments of the game
            $comments = $this->commentRepository->findByGame($game);
        // load 3 games with the same genre
            $gamesSameGenre = $this->gameRepository->findThreeByGenre($game[0]->getGenres()[0]->getName());

        return $this->render('game_detail/index.html.twig', [
            'game' => $game,
            'commentData' => $commentData,
            'comments' => $comments,
            'gamesSameGenre' => $gamesSameGenre,
        ]);
    }
}
