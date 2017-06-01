<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 01/06/17
 * Time: 17:48
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Question;
use Doctrine\ORM\EntityRepository;

class PropositionRepository extends EntityRepository
{
    /**
     * @param Question $question
     * @return static
     */
    public function removeAllChilds(Question $question) {
        $this->_em->createQuery(sprintf('UPDATE %s p SET p.question = NULL WHERE p.question = :id', $this->_entityName))
            ->execute(['id' => $question->getId()]);
    }

    /**
     * @param array $criteria
     */
    public function deleteBy(array $criteria) {
        $query = $this->_em->createQuery();
        $dql = 'DELETE %s p';

        if (!empty($criteria)) {
            $where = ' WHERE';
            $opr = ' ';

            foreach ($criteria as $key => $value) {
                $where .= sprintf('%2$sp.%1$s = :%1$s', $key, $opr);
                $query->setParameter($key, $value);
                $opr = ' AND ';
            }

            $dql .= $where;
        }

        $query->setDQL(sprintf($dql, $this->_entityName))->execute();
    }
}