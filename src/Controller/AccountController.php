<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account/{slug}', name: 'app_detail_account')]
    public function index(AccountRepository $accountRepository, string $slug): Response
    {
        // Find all user information by slug
            $account = $accountRepository->findAllInfo($slug);

        return $this->render('account/index.html.twig', [
            'account' => $account[0],
        ]);
    }
}
