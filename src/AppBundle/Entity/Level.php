<?php
namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Level
 * @ORM\Entity()
 * @ORM\Table(name="level")
 * @author Ange Paterson
 */
class Level
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int $label
     * @ORM\Column(type="string")
     */
    private $label;


    /**
     * @var Collection $question
     * @ORM\OneToMany(targetEntity="Question", mappedBy="level", fetch="EXTRA_LAZY")
     */
    private $questions;

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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param int $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
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
     * @param $questions
     * @return $this
     */
    public function setQuestions($questions)
    {
        $this->questions = new ArrayCollection();

        foreach ($questions as $question) {
            $this->addQuestion($question);
        }
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Question $question
     * @return $this
     */
    public function addQuestion(Question $question){
        if (false === $this->questions->contains($question))
        {
            $this->questions->add($question);
        }
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Question $question
     * @return $this
     */
    public function removeQuestion(Question $question){
        if (false === $this->questions->contains($question))
        {
            $this->questions->removeElement($question);
        }

        return $this;
    }

}