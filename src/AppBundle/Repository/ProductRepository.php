<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    public function getProductsWithPictures()
    {
        return $query = $this->createQueryBuilder('p')
            ->select('p, pic')
            ->leftJoin('p.pictures', 'pic')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();
    }

    public function getProductWithJoins($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, pic')
            ->leftJoin('p.pictures', 'pic')
            ->where('p.slug = ?1')
            ->setParameter(1, $slug)
            ->getQuery()
            ->getSingleResult();
    }

    public function getLatestProductsWithPictures($max = 9)
    {
        return $this->createQueryBuilder('p')
            ->select('p, pic')
            ->leftJoin('p.pictures', 'pic')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult(1)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    public function getFilteredProductsWithPictures($filter, $params)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p, pic, cat', 'opt')
            ->leftJoin('p.pictures', 'pic')
            ->leftJoin('p.category', 'cat')
            ->leftJoin('p.attributeValues', 'opt')
            ->orderBy('p.createdAt', 'DESC');

        switch ($filter) {
            case 'category':
                $query
                    ->where('cat.slug = ?1')
                    ->setParameter(1, $params['category']);
                break;
            case 'filter':
                $query->where('cat.id = :category');
                $paramsData['category'] = $params['category'];
                if (isset($params['options']) && count($params['options']) > 0) {
                    $optionsConditions = '';
                    foreach ($params['options'] as $key => $value) {
                        if ($optionsConditions !== '') {
                            $optionsConditions .= ' OR ';
                        }
                        $optionsConditions .= 'opt.attributeOption = (:option' . $key . ')';
                        $paramsData['option' . $key] = $value;
                    }
                    $query->andWhere($optionsConditions);
                }
                $query->setParameters($paramsData);
                break;
            case 'search':
                $query
                    ->where('p.name like ?1')
                    ->setParameter(1, '%' . $params['name'] . '%');
                break;
        }

        return $query->getQuery();
    }
}
