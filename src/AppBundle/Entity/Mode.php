<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 23/05/17
 * Time: 17:57
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Mode
 * @package AppBundle\Entity
 * @ORM\Table()
 * @ORM\Entity()
 * @author joel
 */
class Mode
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Quiz", mappedBy="mode", fetch="EXTRA_LAZY")
     */
    private $quizs;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return Collection
     */
    public function getQuizs()
    {
        return $this->quizs;
    }

    /**
     * @param Quiz $quiz
     * @return $this
     */
    public function addQuizs(Quiz $quiz)
    {
        if (false === $this->quizs->contains($quiz))
        {
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
        foreach ($quizs as $quiz)
        {
            $this->addQuizs($quiz);
        }
        return $this;
    }

    /**
     * @param Quiz $quiz
     * @return $this
     */
    public function removeQuizs(Quiz $quiz)
    {
        if (true === $this->quizs->contains($quiz))
        {
            $this->quizs->removeElement($quiz);
        }
        return $this;
    }




}