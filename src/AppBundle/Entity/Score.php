<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Score
 * @package AppBundle\Entity
 * @author Peflyn Ange Paterson
 * @ORM\Table()
 * @ORM\Entity()
 * @JMS\ExclusionPolicy("all")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="scores"))
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @var Quiz $quiz
     */
    private $quiz;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="scores"))
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Question $question
     */
    private $question;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $delivered
     * @JMS\Expose()
     */
    private $delivered;

    /**
     * @ORM\Column(name="matching", type="boolean")
     * @var boolean $match
     * @JMS\Expose()
     */
    private $matching;


    public function __construct(Question $question, Quiz $quiz)
    {
        $this->matching = false;
        $this->delivered = false;
        $this->setQuiz($quiz);
        $this->setQuestion($question);
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
        $quiz->addScore($this);
        return $this;
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
     * @return $this
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        $question->addScore($this);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDelivered()
    {
        return $this->delivered;
    }

    /**
     * @param boolean $delivered
     * @return $this
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMatching(): bool
    {
        return $this->matching;
    }

    /**
     * @param bool $matching
     * @return $this
     */
    public function setMatching(bool $matching)
    {
        $this->matching = $matching;
        return $this;
    }




}