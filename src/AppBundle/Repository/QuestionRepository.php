<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 01/06/17
 * Time: 16:33
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Level;
use AppBundle\Entity\Quiz;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * @param Category $category
     */
    public function detachAllChild(Category $category){
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

    /**
     * @param int $number
     * @param Category|null $category
     * @param Level|null $level
     * @return array
     */
    public function findForQuiz(int $number, Category $category = null, Level $level = null) {
        $criteria = [];

        if ($category !== null) {
            $criteria['category'] = $category->getId();
        }
        if ($level !== null) {
            $criteria['level'] = $level->getId();
        }

        return $this->findBy($criteria, [], $number);
    }

    public function findOneByQuiz(Quiz $quiz) {

        $query = $this->_em->createQuery(sprintf('SELECT q FROM %s q JOIN q.scores s JOIN s.quiz qu WHERE qu.id = :id AND s.delivered = FALSE', $this->_entityName))
            ->setParameter('id',$quiz->getId())
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $query->getOneOrNullResult();
    }

}