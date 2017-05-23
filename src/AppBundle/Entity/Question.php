<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="question")
 * @ORM\Entity()
 *
 * @author Ange Paterson
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string $content
     */
    private $content;

    /**
     * @var int $duration
     * @ORM\Column(type="smallint", options={"unsigned": true})
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $multipleChoice
     */
    private $multipleChoice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="questions", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var Category $category
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Proposition", mappedBy="questions", fetch="EAGER")
     * @var Collection $propositions
     */
    private $propositions;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Score", mappedBy="question")
     */
    private $scores;


    public function __construct()
    {
        $this->multipleChoice = false;
        $this->propositions = new ArrayCollection();
        $this->scores = new ArrayCollection();

    }

    /**
     * @return bool
     */
    public function hasMultipleChoice()
    {
        return $this->multipleChoice;
    }

    /**
     * @param bool $multipleChoice
     * @return $this
     */
    public function setMultipleChoice($multipleChoice)
    {
        $this->multipleChoice = $multipleChoice;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
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
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPropositions()
    {
        return $this->propositions;
    }

    /**
     * @param Proposition $proposition
     * @return $this
     */
    public function addProposition(Proposition $proposition)
    {
        if (false=== $this->propositions->contains($proposition))
        {
            $this->propositions->add($proposition);
        }
        return $this;
    }

    /**
     * @param Collection $propositions
     * @return $this
     */
    public function setPropositions(Collection $propositions)
    {
        $this->propositions = new ArrayCollection();
        foreach ($propositions as $proposition){
            $this->addProposition($proposition);
        }
        return $this;
    }

    /**
     * @param Proposition $proposition
     * @return $this
     */
    public function removeProposition(Proposition $proposition)
    {
        if (true === $this->propositions->contains($proposition)){
            $this->propositions->removeElement($proposition);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * @param Score $score
     * @return $this
     */
    public function addScore(Score $score)
    {
        if (false === $this->scores->contains($score))
        {
            $this->scores->add($score);
        }
        return $this;
    }

    /**
     * @param Collection $scores
     * @return $this
     */
    public function setScores($scores)
    {
        $this->scores = new ArrayCollection();
        foreach ($scores as $score)
        {
            $this->addScore($score);
        }
        return $this;
    }


    /**
     * @param Score $score
     * @return $this
     */
    public function removeScore(Score $score)
    {
        if (true === $this->scores->contains($score))
        {
            $this->scores->removeElement($score);
        }
        return $this;
    }

}