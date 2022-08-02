<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        GameRepository $gameRepository,
        GenreRepository $genreRepository,
        CommentRepository $commentRepository,
    ): Response
    {
        // Afficher les 9 jeux par temps de jeux
            $gamesByTime = $gameRepository->findByMostPlayed();
        // Afficher les 4 dernières sorties (sur une ligne)
            $gamesByLastRelease = $gameRepository->findByLastRelease();
        // Afficher les 4 derniers commentaires
            $lastComment = $commentRepository->findByLastComment();
        // Afficher les 9 jeux les plus vendus (présent dans la table library)
            $gamesByBestSeller = $gameRepository->findByBestSeller();
        // Afficher 9 genres par ordre alphabétique
            $categories = $genreRepository->findNineGenres();

        return $this->render('home/index.html.twig', [
            'gamesByTime' => $gamesByTime,
            'gamesByLastRealese' => $gamesByLastRelease,
            'lastComment' => $lastComment,
            'gamesByBestSeller' => $gamesByBestSeller,
            'categories' => $categories,
        ]);
    }
}
