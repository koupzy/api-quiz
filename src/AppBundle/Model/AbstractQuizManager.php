<?php
namespace AppBundle\Model;

use AppBundle\Entity\Category;
use AppBundle\Entity\Level;
use AppBundle\Entity\Mode;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\Score;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class QuizManager
 * @package AppBundle\Model
 */
abstract class AbstractQuizManager implements QuizManagerInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param Category|null $category
     * @param int|null $nombre
     * @param Level|null $level
     * @param Mode|null $mode
     * @param bool $andFlush
     * @return Quiz
     */
    public function create(User $user = null, Category $category = null, int $nombre = null, Level $level = null, Mode $mode = null,bool $andFlush = true)
    {
        $quiz = new Quiz();
            $quiz->setUser($user);


        if ($category !== null) {
            $quiz->setCategory($category);
        }

        if ($nombre !== null) {
            $quiz->setNumber($nombre);
        }

        if ($level !== null) {
            $quiz->setLevel($level);
        }

        if ($mode !== null) {
            $quiz->setMode($mode);
        }

        $this->entityManager->persist($quiz);

        if ($andFlush === true) {
            $this->entityManager->flush($quiz);
        }

        return $quiz;
    }

    public function start(Quiz $quiz)
    {

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository(Question::class)->findForQuiz($quiz->getNumber(),$quiz->getCategory(),$quiz->getLevel());
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
        $question = $this->getDoctrine->getManager()
                    ->getRepository(Question::class)->findOneByScore($quiz);
        return $question;
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