<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 19/05/17
 * Time: 09:36
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Quiz
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 *
 * @author joel
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned= true"})
     * @var integer $id
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="created_at",type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updated_at",type="datetime")
     */
    private $updatedAt;

    /**
     * @var boolean $pause
     * @ORM\Column(type="boolean")
     */
    private $paused;

    /**
     * @var boolean $stop
     * @ORM\Column(type="boolean")
     */
    private $finished;

    /**
     * @var integer $note
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="quizs", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;

    /**
     * @var Collection $scores
     * @ORM\OneToMany(targetEntity="Score", mappedBy="quiz")
     */
    private $scores;

    /**
     * @var Mode
     * @ORM\ManyToOne(targetEntity="Mode", inversedBy="quizs")
     * @ORM\JoinColumn(name="mode_id", referencedColumnName="id")
     */
    private $mode;

    public function __construct()
    {
        $this->paused == false;
        $this->finished == false;
        $this->scores == new ArrayCollection();

    }

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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    /**
     * @return bool
     */
    public function isPaused()
    {
        return $this->paused;
    }

    /**
     * @param bool $paused
     * @return $this
     */
    public function setPaused($paused)
    {
        $this->paused = $paused;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

    /**
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param int $note
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }



    /**
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @param Collection $scores
     * @return $this
     */
    public function setScores(Collection $scores)
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

    public function addScore(Score $score)
    {
        if (false === $this->scores->contains($score))
        {
            $this->scores->add($score);
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

    /**
     * @return Mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param Mode $mode
     * @return $this
     */
    public function setMode(Mode $mode)
    {
        $this->mode = $mode;
    }

}