<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    
    /**
     * The function findByStagiairesNotInSession retrieves a list of Stagiaire entities that are not
     * associated with a specific Session entity.
     * 
     * @param int idsession The parameter `idsession` is an integer that represents the ID of a
     * session.
     * 
     * @return array a list of Stagiaire entities that are not associated with a specific session.
     */
    public function findByStagiairesNotInSession(int $idsession)
    {
        $em = $this->getEntityManager(); // get the EntityManager
        $sub = $em->createQueryBuilder(); // create a new QueryBuilder

        $qb = $sub; // use the same QueryBuilder for the subquery

        $qb->select('s') // select the root alias
            ->from('App\Entity\Stagiaire', 's') // the subquery is based on the same entity
            ->leftJoin('s.sessions', 'se') // join the subquery
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder(); // create a new QueryBuilder

        $sub->select('st')->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL())) //Ici, on compare les deux requêtes !
            ->setParameter('id', $idsession);

        return $sub->getQuery()->getResult();
    }


    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
