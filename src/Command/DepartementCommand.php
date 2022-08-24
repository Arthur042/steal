<?php

namespace App\Command;

use App\Entity\Department;
use App\Service\HttpClientConnector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:departement',
    description: 'Add a short description for your command',
)]
class DepartementCommand extends Command
{
    public function __construct(
        private HttpClientConnector $httpClient,
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $departments = $this->httpClient->urlConnect('https://geo.api.gouv.fr/departements');   // request to the API
        $departments = json_decode($departments->getContent(), true);       // decode the response (true = convertit en tableau associatif)
        $countDept = count($departments);
        $count = 0;

        $progressBar = new ProgressBar($output, $countDept);
        $progressBar->start();

        foreach ($departments as $department) {
            // persist each department name and code
            $newDepartment = new Department();
            $newDepartment->setName($department['nom']);
            $newDepartment->setCode($department['code']);
            $this->entityManager->persist($newDepartment);
            $count++;
            $progressBar->advance();
        }

        if ($count === $countDept) {
            $this->entityManager->flush();
            $io->success('Tous les départements ont été ajouté');
            $progressBar->finish();
            return self::SUCCESS;
        }

        $io->error('Une erreur c\'est produite');
        return self::FAILURE;
    }
}
