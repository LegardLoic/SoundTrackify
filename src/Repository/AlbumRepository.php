<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function add(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get four random album with DQL
     *
     * @return object
     */
    public function findFourRandomAlbum()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
                FROM App\Entity\Album AS a
                ORDER BY RAND()'
        );
        return $query->setMaxResults(4)->getResult();
    }

    /**
     * Get all albums order by name with SQL
     *
     * @return object
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get one album by slug
     *
     * @param [type] $slug
     * @return object
     */
    public function findBySlug($slug)
    {
        return $this->createQueryBuilder('a')
            ->where('a.slug = :slug')
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
     * @return object
     */
    public function search($query, $limit = null, $offset = null)
    {
        $stopWords = ['et', 'ou', 'de', 'the', 'of']; // List of words to ignore
        $words = explode(' ', $query);
        $words = array_diff($words, $stopWords); // Filter empty words

        $qb = $this->createQueryBuilder('a');

        foreach ($words as $key => $word)
        {
            $qb->orWhere('LOWER(a.name) LIKE LOWER(:word' . $key . ')')
            ->setParameter('word' . $key, '%' . $word . '%');
        }

        // Sort results, for example by creation date
        $qb->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Get one album for the dashboard home page
     *
     * @return object
     */
    public function findOneForDashboard()
    {
        return $this->createQueryBuilder('a')
           ->orderBy('a.createdAt', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult();
    }
}
