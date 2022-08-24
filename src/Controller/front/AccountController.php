<?php

namespace App\Controller\front;

use App\Entity\Account;
use App\Form\EditAccountType;
use App\Repository\AccountRepository;
use App\Service\FileUploader;
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
        FileUploader $fileUploader
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
            /** @var Account $data */
            $data = $form->getData();
            if ($form->get('pathImage')->getData() !== null) {
                $file = $fileUploader->uploadFile(
                    $form->get('pathImage')->getData(),
                    '/profile'
                );
                $data->setPathImage($file);
            }
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
