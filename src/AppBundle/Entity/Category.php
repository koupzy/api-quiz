<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="category")
 * @UniqueEntity(fields="name")
 * @author Ange Paterson
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="smallint", options={"unsigned": true})
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string $name
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Question", mappedBy="category", fetch="EXTRA_LAZY")
     * @var Collection $questions
     */
    private $questions;

    /**
     * @var\Collection $quizs
     * @ORM\OneToMany(targetEntity="Quiz", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $quizs;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this;
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
        foreach ($questions as $question) {
            $this->addQuestion($question);
        }
        return $this;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function removeQuestion(Question $question)
    {
        if (true === $this->questions->contains($question)) {
            $this->questions->removeElement($question);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuizs()
    {
        return $this->quizs;
    }

    /**
     * @param Quiz $quiz
     * @return $this
     */
    public function addQuizs(Quiz $quiz) {
        if (false === $this->quizs->contains($quiz)) {
            $this->quizs->add($quiz);
        }
        return $this;
    }

    /**
     * @param Collection $quizs
     * @return $this
     */
    public function setQuizs(Collection $quizs)
    {
        $this->quizs = new ArrayCollection();
        foreach ($quizs as $quiz) {
            $this->addQuizs($quiz);
        }
        return $this;
    }

    /**
     * @param Quiz $quiz
     * @return $this
     */
    public function removeQuizs(Quiz $quiz) {
        if (true === $this->quizs->contains($quiz)) {
            $this->quizs->removeElement($quiz);
        }
        return $this;
    }


}