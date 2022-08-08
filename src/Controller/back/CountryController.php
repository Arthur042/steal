<?php

namespace App\Controller\back;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/country')]
class CountryController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private CountryRepository $countryRepository,
        private EntityManagerInterface $entityManager,
        private TextService $textService,
    ){}

    #[Route('/', name: 'app_country_admin')]
    public function index(Request $request): Response
    {
        $countries = $this->paginator->paginate(
            $this->countryRepository->getQbAll(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('back/country/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('/new', name: 'app_country_admin_new')]
    public function new(Request $request): Response
    {
        return $this->handleForm(
            new Country(), $request
        );
    }

    #[Route('/edit/{slug}', name: 'app_country_admin_edit')]
    public function edit(Request $request, Country $country): Response
    {
        return $this->handleForm(
            $country, $request, 'modifier un pays'
        );
    }

    public function handleForm(
        Country $country,
        Request $request,
        string $title = 'Ajouter un pays'
    ): Response {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Country $data */
            $data = $form->getData();
            $data->setSlug($this->textService->slugify($data->getNationality()));
            $data->setUrlFlag('https://flagcdn.com/32x24/'.$data->getCode().'.png');
            $this->entityManager->persist($data); // => insert into country
            $this->entityManager->flush(); // on tire la chasse => COMMIT
            return $this->redirectToRoute('app_country_admin');
        }

        return $this->render('back/partial/form.html.twig', [
            'form' => $form->createView(),
            'link' => 'app_country_admin',
            'title' => $title,
            'liste' => 'Pays',

        ]);
    }
}
