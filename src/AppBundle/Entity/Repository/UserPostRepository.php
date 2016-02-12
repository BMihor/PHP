<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\UserPost;
use Doctrine\ORM\EntityRepository;


Class UserPostRepository extends EntityRepository
{

    public function findALLPopularPostsOrderBy($orderBy)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->where('q.status = :status')
            ->andWhere('q.isPopular = :isPopular')
            ->setParameter('status', UserPost::STATUS_PUBLISH)
            ->setParameter('isPopular', UserPost::STATUS_POPULAR)
            ->orderBy('q.createdDate', $orderBy);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findALLPostsOrderByLimit($orderBy, $limit)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->where('q.status = :status')
            ->setParameter('status', UserPost::STATUS_PUBLISH)
            ->orderBy('q.createdDate', $orderBy)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();

    }

    public function findALLPostsOrderBy($orderBy)
    {
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->where('q.status = :status')
            ->setParameter('status', UserPost::STATUS_PUBLISH)
            ->orderBy('q.createdDate', $orderBy);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findLatestPosts()
    {
        $date = new \DateTime();

        $date->modify('-1 day');

        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder
            ->where('q.status = :status')
            ->andWhere('q.createdDate >= :date')
            ->setParameter('status', UserPost::STATUS_PUBLISH)
            ->setParameter('date', $date)
            ->orderBy('q.createdDate', 'DESC')
            ->setMaxResults(3);

        return $queryBuilder->getQuery()->getResult();
    }
}
