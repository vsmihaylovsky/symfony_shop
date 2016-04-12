<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getFirstLevel()
    {
        return $this->createQueryBuilder('c')
            ->select('c, children')
            ->leftJoin('c.children', 'children')
            ->where('c.parent is null')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getLastLevelsForCategories()
    {
        return $this->createQueryBuilder('c')
            ->where('c.hasProducts is null')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getLastLevelsForProducts()
    {
        return $this->createQueryBuilder('c')
            ->where('c.hasChildren is null')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCategoryWithCounts()
    {
        return $this->createQueryBuilder('c')
            ->select('c, count(cc.id) as countChildren, cp.id as parentId, count(cpr.id) as countProducts')
            ->leftJoin('c.children', 'cc')
            ->leftJoin('c.parent', 'cp')
            ->leftJoin('c.products', 'cpr')
            ->groupBy('c, parentId')
            ->orderBy('c.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
