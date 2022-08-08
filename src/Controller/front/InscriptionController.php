<?php

namespace App\Controller\front;

use App\Entity\Account;
use App\Form\AccountType;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscriptionModal(
        Request $request,
        EntityManagerInterface $entityManager,
        TextService $textService,
    ): Response
    {
        $form = $this->createForm(AccountType::class, new Account());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $data */
            $data = $form->getData();
            $data->SetSlug($textService->slugify($data->getName()));
            $data->SetCreatedAt(new \DateTime());
            $data->SetWallet(0);
            $entityManager->persist($data);
            $entityManager->flush();
            return $this->redirectToRoute('app_detail_account', [
                'slug' => $data->getSlug(),
            ]);
        }

        return $this->render('front/inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
