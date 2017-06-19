<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 15/06/17
 * Time: 14:54
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param array $criteria
     */
    public function deleteBy(array $criteria)
    {
        $query =$this->_em->createQuery();
        $dql = 'DELETE %s u';

        if (!empty($criteria))
        {
            $where = ' WHERE';
            $opr = ' ';

            foreach ($criteria as $key => $value)
            {
                $where .= sprintf('%2$su.%1$s = :%1$s', $key, $opr);
                $query->setParameter($key, $value);
                $opr = ' AND ';
            }

            $dql .= $where;
        }

        $query->setDQL(sprintf($dql, $this->_entityName))->execute();
    }

}