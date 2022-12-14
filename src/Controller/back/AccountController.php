<?php

namespace App\Controller\back;

use App\Form\Filter\AccountFilterType;
use App\Repository\AccountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
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
        FilterBuilderUpdaterInterface $builderUpdater,
    ): Response
    {
        $qb = $accountRepository->getQbAll();

        $filterForm = $this->createForm(AccountFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        $accounts = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('back/account/index.html.twig', [
            'accounts' => $accounts,
            'filters' => $filterForm->createView(),
        ]);
    }
}
