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
    public function create(User $user, Category $category = null, int $nombre = null, Level $level = null, Mode $mode = null, bool $andFlush = true)
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

    /**
     * @param Quiz $quiz
     */
    public function start(Quiz $quiz)
    {
        $questions = $this->entityManager->getRepository(Question::class)->findForQuiz($quiz->getNumber(),$quiz->getCategory(),$quiz->getLevel());
        $scores = [];

        foreach ($questions as $question) {
            $score = new Score($question, $quiz);
            $this->entityManager->persist($score);
            $scores[] = $score;
        }
        unset($score);

        $this->entityManager->flush($scores);
    }

    /**
     * @param Quiz $quiz
     * @return Question
     */
    public function delivery(Quiz $quiz)
    {
        $question = $this->entityManager->getRepository(Question::class)->findOneByQuiz($quiz);
        return $question;
    }

    /**
     * @param Quiz $quiz
     */
    public function pause(Quiz $quiz)
    {
        $quiz->setPaused(true);
    }

    /**
     * @param Quiz $quiz
     */
    public function stop(Quiz $quiz)
    {
        $quiz->setFinished(true);
    }

    /**
     * @param Quiz $quiz
     */
    public function resume(Quiz $quiz)
    {
        $quiz->setPaused(false);
    }
}