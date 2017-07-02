<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 15/06/17
 * Time: 17:36
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Level;
use AppBundle\Entity\Quiz;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

class QuizRepository extends EntityRepository
{
    /**
     * @param array $criteria
     */
    public function deleteBy(array $criteria)
    {
        $query =$this->_em->createQuery();
        $dql = 'DELETE %s q';

        if (!empty($criteria))
        {
            $where = ' WHERE';
            $opr = ' ';

            foreach ($criteria as $key => $value)
            {
                $where .= sprintf('%2$sq.%1$s = :%1$s', $key, $opr);
                $query->setParameter($key, $value);
                $opr = ' AND ';
            }

            $dql .= $where;
        }

        $query->setDQL(sprintf($dql, $this->_entityName))->execute();
    }

    public function selectAll(Quiz $quiz){
        $this->_em->createQuery(sprintf('SELECT * FROM %s q  WHERE q.user = :id', $this->_entityName))
            ->execute(['id' => $quiz->getId()]);
    }

    /**
     * @param Level $level
     */
    public function detachAllChild(Level $level){
        $this->_em->createQuery(sprintf('UPDATE %s q SET q.level = NULL WHERE q.level = :id', $this->_entityName))
            ->execute(['id' => $level->getId()]);
    }


}