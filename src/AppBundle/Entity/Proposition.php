<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="proposition")
 * @ORM\Entity()
 *
 * @author Ange Paterson
 */
class Proposition
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @var string $content
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @var integer $point
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="propositions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Collection $questions
     */
    private $questions;

    /**
     * @var boolean
     */
    private $truth;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return $this;
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param int $point
     */
    public function setPoint($point)
    {
        $this->point = $point;
    }



    /**
     * @return Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function addQuestion(Question $question)
    {
        if (false === $this->questions->contains($question)) {
            $this->questions->add($question);
        }
        return $this;
    }

    /**
     * @param Collection $questions
     * @return $this
     */
    public function setQuestions(Collection $questions)
    {
        $this->questions = new ArrayCollection();
        foreach ($questions as $question){
            $this->addQuestion($question);
        }
        return $this;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function removeQuestion(Question $question){
        if (true === $this->questions->contains($question))
        {
            $this->questions->removeElement($question);
        }
        return $this;
    }


}