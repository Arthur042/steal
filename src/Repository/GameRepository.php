<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findByMostPlayed(): array
    {
        return  $this->createQueryBuilder('game')
            ->leftjoin('game.libraries', 'library')
            ->groupBy('library.game')
            ->orderBy('SUM(library.gameTime)', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
            ;

    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findByLastRelease(): array
    {
        return  $this->createQueryBuilder('g')
                ->orderBy('g.publishedAt', 'DESC')
                ->setMaxResults(4)
                ->getQuery()
                ->getResult();

    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findByBestSeller(): array
    {
        return  $this->createQueryBuilder('g')
                ->leftJoin('g.libraries', 'l')
                ->groupBy('l.game')
                ->orderBy( 'count(l.game)', 'DESC')
                ->setMaxResults(9)
                ->getQuery()
                ->getResult();
    }

    public function findOneBySlug(string $slug): mixed
    {
        return  $this->createQueryBuilder('game')
                ->select('game, comment, country, genre, publisher')
                ->leftJoin('game.comments', 'comment')
                ->join('game.country', 'country')
                ->join('game.genres', 'genre')
                ->leftJoin('game.publisher', 'publisher')
                ->where('game.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getResult()
            ;
    }

    public function findThreeByGenre(string $genre): array
    {
        return  $this->createQueryBuilder('game')
                ->join('game.genres', 'genre')
                ->where('genre.name = :genre')
                ->setParameter('genre', $genre)
                ->setMaxResults(3)
                ->getQuery()
                ->getResult()
            ;
    }

    public function findByNameLike(string $name): array
    {
        return  $this->createQueryBuilder('game')
                ->select('game.name, game.slug, game.thumbnailCover, game.price')
                ->where('game.name LIKE :name')
                ->setParameter('name', '%'.$name.'%')
                ->getQuery()
                ->getResult()
            ;
    }

    public function getQbAll()
    {
        return $this->createQueryBuilder('game')
            ->select('game, publisher')
            ->leftJoin('game.publisher', 'publisher');
    }

}
