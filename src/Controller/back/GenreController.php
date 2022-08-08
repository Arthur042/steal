<?php

namespace App\Controller\back;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/genre')]
class GenreController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private GenreRepository $genreRepository,
        private EntityManagerInterface $entityManager,
        private TextService $textService,
    ){}

    #[Route('/', name: 'app_genre_admin')]
    public function index(Request $request): Response
    {
        $genres = $this->paginator->paginate(
            $this->genreRepository->getQbAll(),
            $request->query->getInt('page', 1),
            15
        );


        return $this->render('back/genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/new', name: 'app_genre_admin_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm(
            new Genre(), $request
        );
    }

    #[Route('/edit/{slug}', name: 'app_genre_admin_edit')]
    public function edit(Request $request, Genre $genres): Response
    {
        return $this->handleForm(
            $genres, $request, 'Modifier une Catégorie'
        );
    }

    public function handleForm(
        Genre $genres,
        Request $request,
        string $title = 'Ajouter une catégorie'
    ): Response
    {
        $form = $this->createForm(GenreType::class, $genres);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Genre $data */
            $data = $form->getData();
            $data->SetSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($genres);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_genre_admin');
        }

        return $this->render('back/partial/form.html.twig', [
            'form' => $form->createView(),
            'link' => 'app_genre_admin',
            'title' => $title,
            'liste' => 'catégories',
        ]);
    }
}
