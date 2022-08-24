<?php

namespace App\Controller\back;

use App\Repository\PublisherRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route('/admin/publisher/pdf', name: 'app_admin_publisher_pdf')]
    public function index(
        PublisherRepository $publisherRepository,
        Pdf $knpSnappyPdf,
    ): PdfResponse
    {
        $totalSells = $publisherRepository->getTotalSell();
        $totalSell = 0;
        $totalCa = 0;

        $html = $this->renderView('back/publisher/pdf/index.html.twig', [
            'totalSells' => $totalSells,
            'totalSell' => $totalSell,
            'totalCa' => $totalCa,
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'chiffre_d\'affaire.pdf',
        );
    }
}
