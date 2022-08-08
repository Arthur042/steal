<?php

namespace App\Controller\front;

use App\Entity\Country;
use App\Form\EditAccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    #[Route('/{slug}', name: 'app_detail_account')]
    public function index(
        AccountRepository $accountRepository,
        string $slug,
        Request $request,
    ): Response
    {
        // Find all user information by slug
            $account = $accountRepository->findAllInfo($slug);
            if(isset($account[0])){
                $account = $account[0];
            }

        $form = $this->createForm(EditAccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Country $data */
            $data = $form->getData();
            $this->entityManager->persist($data); // => insert into country
            $this->entityManager->flush(); // on tire la chasse => COMMIT
            return $this->redirectToRoute('app_detail_account', [
                'slug' => $account->getSlug(),
            ]);
        }

        return $this->render('front/account/index.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }
}
