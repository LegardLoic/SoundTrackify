<?php

namespace App\Repository;

use App\Entity\Videogame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Videogame>
 *
 * @method Videogame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Videogame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Videogame[]    findAll()
 * @method Videogame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideogameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Videogame::class);
    }

    public function add(Videogame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Videogame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get the last four video games created with QL
     *
     * @return void
     */
    public function findLastFourForHomepage()
    {
        // the last 4 games, sorted by releaseDate DESC, LIMIT = 4
        return $this->createQueryBuilder('v')
            ->orderBy('v.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all videogames order by name with DQL
     *
     * @return void
     */
    public function findAllOrderByName()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT v
                FROM App\Entity\Videogame AS v
                ORDER BY v.name'
        );
        return $query->getResult();
    }

    /**
     * Get one videogame by slug
     *
     * @param [type] $slug
     * @return void
     */
    public function findBySlug($slug)
    {
        return $this->createQueryBuilder('v')
            ->where('v.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * search custom request
     *
     * @param [type] $query
     * @param [type] $limit
     * @param [type] $offset
     * @return void
     */
    public function search($query, $limit = null, $offset = null)
    {
        $stopWords = ['et', 'ou', 'de', 'the', 'of']; // List of words to ignore
        $words = explode(' ', $query);
        $words = array_diff($words, $stopWords); // Filter empty words

        $qb = $this->createQueryBuilder('v');

        foreach ($words as $key => $word)
        {
            $qb->orWhere('LOWER(v.name) LIKE LOWER(:word' . $key . ')')
            ->setParameter('word' . $key, '%' . $word . '%');
        }

        // Sort results, for example by creation date
        $qb->orderBy('v.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Get one videogame for the dashboard home page
     *
     * @return void
     */
    public function findOneForDashboard()
    {
        return $this->createQueryBuilder('v')
           ->orderBy('v.createdAt', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult();
    }
}
