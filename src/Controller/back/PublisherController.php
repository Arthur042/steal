<?php

namespace App\Controller\back;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/publisher')]
class PublisherController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private PublisherRepository $publisherRepository,
        private EntityManagerInterface $entityManager,
        private TextService $textService,
    ){}

    #[Route('/', name: 'app_publisher_admin')]
    public function index(Request $request): Response
    {
        $publishers = $this->paginator->paginate(
            $this->publisherRepository->getQbAll(),
            $request->query->getInt('page', 1),
            12
        );


        return $this->render('back/publisher/index.html.twig', [
            'publishers' => $publishers,
        ]);
    }

    #[Route('/new', name: 'app_publisher_admin_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm(
            new Publisher(), $request
        );
    }

    #[Route('/edit/{slug}', name: 'app_publisher_admin_edit')]
    public function edit(Request $request, Publisher $publisher): Response
    {
        return $this->handleForm(
            $publisher, $request, 'Modifier un éditeur'
        );
    }

    public function handleForm(
        Publisher $publisher,
        Request $request,
        string $title = 'Ajouter un éditeur'
    ): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Publisher $data */
            $data = $form->getData();
            $data->SetSlug($this->textService->slugify($data->getName()));
            $this->entityManager->persist($publisher);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_publisher_admin');
        }

        return $this->render('back/partial/form.html.twig', [
            'form' => $form->createView(),
            'link' => 'app_publisher_admin',
            'title' => $title,
            'liste' => 'éditeurs',
        ]);
    }
}
