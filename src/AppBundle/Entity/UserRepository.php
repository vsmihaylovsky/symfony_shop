<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getAllQuery($search)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username like :username or u.email like :email')
            ->setParameters(['username' => "%$search%", 'email' => "%$search%"])
            ->getQuery();
    }

}
