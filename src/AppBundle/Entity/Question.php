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
     */protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string $content
     */
    protected $content;

    /**
     * @var int $duration
     * @ORM\Column(type="smallint", options={"unsigned": true})
     */
    protected $duration;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $multipleChoice
     */
    protected $multipleChoice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="questions", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @var Category $category
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Proposition", mappedBy="questions", fetch="EAGER")
     * @var Collection $propositions
     */
    protected $propositions;

    public function __construct()
    {
        $this->multipleChoice = false;
        $this->propositions = new ArrayCollection();
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

}