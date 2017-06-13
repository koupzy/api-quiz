<?php
namespace AppBundle\Model;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\Score;

/**
 * Class QuizManager
 * @package AppBundle\Model
 */
abstract class QuizManager implements QuizManagerInterface
{
    public function create()
    {
        // TODO: Implement create() method.
        $quiz = new Quiz();
    }

    public function start(Quiz $quiz)
    {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository(Question::class)->findByQuiz($quiz);
        $sores = [];

        foreach ($questions as $question) {
            $score = new Score($question, $quiz);
            $sores[] = $score;
            $em->persist($score);
        }
        unset($score);

        $em->flush($sores);
    }

    public function delivery(Quiz $quiz)
    {
        $criteria = array('quiz_id' => $quiz->getId(),'delivered'=>false);
        $em = $this->getDoctrine()->getManager();
        $score = $em->getRepository(Score::class)->findOneBy($criteria);
        return $score->getQuestion();
    }

    public function pause(Quiz $quiz)
    {
        $quiz->setPaused(true);
    }

    public function stop(Quiz $quiz)
    {
        $quiz->setFinished(true);
    }

    public function resume(Quiz $quiz)
    {
        $quiz->setPaused(false);
    }


}