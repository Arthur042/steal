<?php

namespace App\Controller\front;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Repository\PublisherRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        GameRepository $gameRepository,
        GenreRepository $genreRepository,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        TextService $textService,
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

        // formulaire
             $form = $this->createForm(AccountType::class, new Account());
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {
                        /** @var Account $data */
                        $data = $form->getData();
                        $data->SetSlug($textService->slugify($data->getName()));
                        $data->SetCreatedAt(new \DateTime());
                        $entityManager->persist($data);
                        $entityManager->flush();
                        return $this->redirectToRoute('app_home');
                    }

        return $this->render('front/home/index.html.twig', [
            'gamesByTime' => $gamesByTime,
            'gamesByLastRealese' => $gamesByLastRelease,
            'lastComment' => $lastComment,
            'gamesByBestSeller' => $gamesByBestSeller,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/search/{search}', name: 'app_search')]
    public function search(
        string $search,
        GameRepository $gameRepository,
        GenreRepository $genreRepository,
        PublisherRepository $publisherRepository,
    ): JsonResponse
    {
        // decode json $search
            $search = json_decode($search, true);

        // Request in the database
            $games = $gameRepository->findByNameLike($search);
            $genres = $genreRepository->findByNameLike($search);
            $publishers = $publisherRepository->findByNameLike($search);

        return (new JsonResponse())->setData([
            'html' => $this->renderView('front/partial/result_search.html.twig', [
                'games' => $games,
                'genres' => $genres,
                'publishers' => $publishers,
            ]),
        ]);
    }
}
