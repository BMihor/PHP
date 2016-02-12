<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\BlogComments;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;


class BlogCommentsRepository extends EntityRepository
{
    public function findALLPostsOrder($orderBy, $id)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->where('q.status = :status')
            ->andWhere('q.post= :id')
            ->setParameter('status', BlogComments::STATUS_PUBLISH)
            ->setParameter('id', $id)
            ->orderBy('q.createdOn', $orderBy);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findALLPostsOrderBy($orderBy, $id)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->select('COUNT(q) as comments')
            ->where('q.status = :status')
            ->andWhere('q.post = :id')
            ->setParameter('status', BlogComments::STATUS_PUBLISH)
            ->setParameter('id', $id)
            ->orderBy('q.createdOn', $orderBy);

        return $queryBuilder->getQuery()->getSingleResult();
    }
}