<?php

namespace App\Controller\back;

use App\Repository\AccountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_admin_account')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        AccountRepository $accountRepository,
    ): Response
    {
        $accounts = $paginator->paginate(
            $accountRepository->findAllAndCountTotalLibrary(),
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/account/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }
}
