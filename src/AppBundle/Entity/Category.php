<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="category")
 *
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
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Question", mappedBy="category", fetch="EXTRA_LAZY")
     * @var Collection $questions
     */
    protected $questions;

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
}