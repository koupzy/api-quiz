<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Score
 * @author Peflyn Ange Paterson
 * @ORM\Table()
 * @ORM\Entity()
 */
class Score
{
    /**
     * @ORM\Column(type="boolean")
     * @var boolean $concord
     */
    private $truth;
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="scoreQuestions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Question $question
     */
    private $question;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz", inversedBy="scoreQuiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @var Quiz $quiz
     */
    private $quiz;

    public function __construct(Question $question, Quiz $quiz)
    {
        $this->truth = false;
        $this->question = $question;
        $this->quiz = $quiz;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     * @return $this
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTruth()
    {
        return $this->truth;
    }

    /**
     * @return $this
     * @param bool $truth
     */
    public function setTruth($truth)
    {
        $this->truth = $truth;
        return $this;
    }


}