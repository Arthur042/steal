<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publisher>
 *
 * @method Publisher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publisher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publisher[]    findAll()
 * @method Publisher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    public function add(Publisher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Publisher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByNameLike(string $name): array
    {
        return  $this->createQueryBuilder('publisher')
            ->select('publisher.name, publisher.slug')
            ->where('publisher.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getQbAll(): QueryBuilder
    {
        return $this->createQueryBuilder('publisher')
            ->select('publisher, country')
            ->join('publisher.country', 'country');
    }

    public function getTotalSell(): array
    {
        return $this->createQueryBuilder('publisher')
            ->select('publisher.name, SUM(game.price) AS chiffreAffaire, COUNT(library.id) AS nbJeu')
            ->join('publisher.games', 'game')
            ->join('game.libraries', 'library')
            ->groupBy('publisher')
            ->orderBy('chiffreAffaire', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

}
