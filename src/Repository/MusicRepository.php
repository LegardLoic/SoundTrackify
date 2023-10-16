<?php

namespace App\Repository;

use App\Entity\Music;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Music>
 *
 * @method Music|null find($id, $lockMode = null, $lockVersion = null)
 * @method Music|null findOneBy(array $criteria, array $orderBy = null)
 * @method Music[]    findAll()
 * @method Music[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Music::class);
    }

    public function add(Music $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Music $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get one music for the dashboard home page
     *
     * @return object
     */
    public function findOneForDashboard()
    {
        return $this->createQueryBuilder('m')
           ->orderBy('m.createdAt', 'DESC')
           ->setMaxResults(1)
           ->getQuery()
           ->getResult();
    }

    /**
     * filter music for back playlists
     *
     * @param [type] $term
     * @return object
     */
    public function findFilteredMusics($term)
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
