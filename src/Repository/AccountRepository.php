<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function add(Account $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Account $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllInfo(string $slug): array
    {
        return $this->createQueryBuilder('account')
            ->select('account, comments, libraries, country, game')
            ->leftJoin('account.comments', 'comments')
            ->leftJoin('account.libraries', 'libraries')
            ->leftJoin('libraries.game', 'game')
            ->leftJoin('account.country', 'country')
            ->where('account.slug = :slug')
            ->orderBy('comments.createdAt', 'DESC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getQbAll(): QueryBuilder
    {
        return $this->createQueryBuilder('account')
                ->select('account', 'country', 'COUNT(libraries) AS total')
                ->leftJoin('account.libraries', 'libraries')
                ->leftJoin('account.country', 'country')
                ->groupBy('account')
                ;
    }
}
