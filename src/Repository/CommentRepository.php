<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByLastComment(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c, u.name AS username, g.name AS gamename')
            ->join('c.account', 'u')
            ->join('c.game', 'g')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countCommentByGame(array $game): array
    {
        return $this->createQueryBuilder('comment')
            ->select('COUNT(comment) AS nbComment, AVG(comment.rank) AS avgRating')
            ->where('comment.game = :game')
            ->groupBy('comment.game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByGame(array $game): array
    {
        return $this->createQueryBuilder('comment')
            ->select('comment, account.name AS username')
            ->join('comment.account', 'account')
            ->where('comment.game = :game')
            ->orderBy('comment.createdAt', 'DESC')
            ->setMaxResults(6)
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult()
            ;
    }
}
