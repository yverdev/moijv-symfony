<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByTag(Tag $tag)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('t = :tag');
        // ->bindParam()
        $queryBuilder->setParameter(':tag', $tag);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    private function createWithAverageQueryBuilder($as)
    {
        return $qb = $this->createQueryBuilder($as)
            ->select($as . ', AVG(c.note) as average') // SELECT p.*, AVG(c.note) FROM product
            ->join($as . '.comments', 'c') // SELECT  p.*, AVG(c.note) FROM product LEFT JOIN comments ...
            ->where('c.note IS NOT NULL') // SELECT ... WHERE ....
            ->groupBy($as); // SELECT ... WHERE ... GROUP BY ...
    }

    public function findWithAverageNote($limit = null)
    {
        $qb = $this->createWithAverageQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
        if($limit) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()
            ->getResult();

        // En DQL :
        // SELECT p, AVG(c.note) as average FROM \App\Entity\Product p JOIN p.comments WHERE c.note IS NOT NULL GROUP BY p
    }

    public function findByCategoryWithAverageNote($category)
    {
        return $this->createWithAverageQueryBuilder('p')
            ->join('p.category', 'ca')
            ->where('ca = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findByTagWithAverageNote($tag)
    {
        return $this->createWithAverageQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('t = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}