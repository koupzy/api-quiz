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
     * @var integer $id
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
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $truth;

    /**
     * @var integer $point
     * @ORM\Column(type="smallint", options={"unsigned": true})
     */
    private $point;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="propositions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @var Question $question
     */
    private $question;

    public function __construct()
    {
        $this->point = 2;
        $this->truth = true;
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
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
        return $this;
    }

}