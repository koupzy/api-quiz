<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 01/06/17
 * Time: 16:33
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * @param Category $category
     */
    public function removeAllChild(Category $category){
        $this->_em->createQuery(sprintf('UPDATE %s q SET q.category = NULL WHERE q.category = :id', $this->_entityName))
        ->execute(['id' => $category->getId()]);
    }

    /**
     * @param array $criteria
     */
    public function deleteBy(array $criteria){
        $query = $this->_em->createQuery();
        $dql = 'DELETE %s q';

        if (!empty($criteria)){
            $where = ' WHERE';
            $opr = ' ';

            foreach ($criteria as $key=>$value){
                $where .= sprintf('%2$sq.%1$s = :%1$s', $key, $opr);
                $query->setParameter($key, $value);
                $opr = ' AND ';
            }

            $dql .= $where;
        }

        $query->setDQL(sprintf($dql, $this->_entityName))->execute();
    }


}