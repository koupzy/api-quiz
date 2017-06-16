<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Score
 * @package AppBundle\Entity
 * @author Peflyn Ange Paterson
 * @ORM\Table()
 * @ORM\Entity()
 */
class Score
{
    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="scores")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $quiz;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="scores")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Question $question
     */
    private $question;

    /**
     * @var boolean $delivered
     * @ORM\Column(type="boolean")
     */
    private $delivered;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $match
     */
    private $match;


    public function __construct(Question $question, Quiz $quiz)
    {
        $this->match = false;
        $this->delivered = false;
        $this->question = $question;
        $this->quiz = $quiz;
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
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
    }



    /**
     * @return bool
     */

    public function isMatch()
    {
        return $this->match;
    }

    /**
     * @return $this
     * @param bool $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
        return $this;
    }

}