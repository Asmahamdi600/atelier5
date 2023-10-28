<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**public function searchref()
     {
        return $this->createQueryBuilder('b') 
        ->where('a.id LIKE:ref ')
        ->setParameter('ref','h%')
        ->getQuery()
        ->getResult();
    }**/
    public function searchbyref($id)
     {
        return $this->createQueryBuilder('b') 
        ->where('b.id=:titre')
        ->setParameter('titre',$id)
        ->getQuery()
        ->getResult();
    }
    public function updatecategory()
    { 
    $entityManager = $this->getEntityManager();
    $query = $entityManager
    ->createQuery(' UPDATE App\Entity\Book b
    SET b.category = :categorynew
    WHERE b.author IN (
        SELECT a.id
        FROM App\Entity\Author a
        WHERE a.username = :authorname)');
    $query->setParameter('categorynew', 'Romance');
    $query->setParameter('authorname', 'William Shakespear');
    $query->getResult();

    }

    public function sommebookbycategory()
    { 
    $entityManager = $this->getEntityManager();
    $query = $entityManager
    ->createQuery(' SELECT Count(b.id) 
    FROM App\Entity\Book b 
    WHERE b.category = :category');
    $query->setParameter('category', 'Science Fiction');
    $query->getResult();
    return $query->getSingleScalarResult();

    }

    public function bookbydate()
    { 
    
    $entityManager = $this->getEntityManager();
    $query = $entityManager
    ->createQuery(' SELECT b
    FROM App\Entity\Book b 
    WHERE b.publicationDate BETWEEN :Date1 AND :Date2');
    $query->setParameter('Date1', new \DateTime("2014-01-01"));
    $query->setParameter('Date2', new \DateTime("2018-12-31"));
    $query->getResult();

    }
    
    

    /**public function updatecategory($nameauthor,$categorynew)
     {
        return $this->createQueryBuilder('b') 
        ->update('App\Entity\Book', 'b')
        ->set('b.category',':newCategory')
        ->Join('b.author','a')
        ->where('a.username=:name')
        ->setParameter('newCategory',$categorynew)
        ->setParameter('name',$nameauthor)
        ->getQuery()
        ->getResult();
    }**/

    public function bookbyauthor()
    {
        return $this->createQueryBuilder('b')
            ->Join('b.author', 'a')
            ->addSelect('a')
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();

    }



    public function bookbyyears()
    {
        return $this->createQueryBuilder('b')
            ->Join('b.author', 'a')
            ->addSelect('a')
            ->where('b.publicationDate<:date')
            ->andWhere('a.nb_books>:count')
            ->setParameter('date', new \DateTime('2023-01-01'))
            ->setParameter('count', 35)
            ->getQuery()
            ->getResult();
        }
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
