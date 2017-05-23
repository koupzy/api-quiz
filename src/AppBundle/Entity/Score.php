<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 23/05/17
 * Time: 14:24
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Score
 * @package AppBundle\Entity
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
     * @var Question
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="scores")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @ORM\Id
     */
    private $question;

    /**
     * @var boolean $note
     * @ORM\Column(type="boolean")
     */
    private $truth;

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
     * @param bool $truth
     * @return $this
     */
    public function setTruth($truth)
    {
        $this->truth = $truth;
        return $this;
    }







}