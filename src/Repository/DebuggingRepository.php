<?php

namespace App\Repository;

use App\Entity\Debugging;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Debugging|null find($id, $lockMode = null, $lockVersion = null)
 * @method Debugging|null findOneBy(array $criteria, array $orderBy = null)
 * @method Debugging[]    findAll()
 * @method Debugging[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebuggingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Debugging::class);
    }

    // /**
    //  * @return Debugging[] Returns an array of Debugging objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Debugging
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

	public function findAllDebuggings()
	{
		return $this->createQueryBuilder('d')
			->orderBy('d.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}

	/**
	 * @return Debugging[]
	 */
	public function findDebuggingsList(Search $search)
	{
		$query = $this->findVisibleQuery();

		if ($search->getTitle()) {
			return $this->createQueryBuilder('d')
				->andWhere('d.title = :title')
				->setParameter('title', $search->getTitle())
				->getQuery()
				->getResult();
		}

		return $query->getQuery();
	}

	public function findVisibleQuery()
	{
		return $this->createQueryBuilder('d')
			->orderBy('d.createdAt', 'DESC');
	}
}
