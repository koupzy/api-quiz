<?php
namespace AppBundle\Entity;

use AppBundle\Model\Categorizable;
use AppBundle\Model\Levelable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @author joel
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Quiz
{
    use Categorizable, Levelable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned= true"})
     * @var integer $id
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="created_at",type="datetime")
     * @JMS\Expose()
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updated_at",type="datetime")
     * @JMS\Expose()
     */
    private $updatedAt;

    /**
     * @var boolean $pause
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     */
    private $paused;

    /**
     * @var boolean $stop
     * @ORM\Column(type="boolean")
     * @JMS\Expose()
     */
    private $finished;

    /**
     * @var integer $note
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $note;

    /**
     * @var integer $number
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private $number;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="quizs", fetch="EXTRA_LAZY"))
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Score", mappedBy="quiz")
     * @var Collection $scores
     */
    private $scores;

    /**
     * @var Category $category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="quizs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @JMS\Expose()
     */
    private $category;

    /**
     * @var Mode
     * @ORM\ManyToOne(targetEntity="Mode", inversedBy="quizs")
     * @ORM\JoinColumn(name="mode_id", referencedColumnName="id")
     */
    private $mode;

    /**
     * @var Level $level
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Level", inversedBy="questions", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="level_id", referencedColumnName="id")
     */
    private $level;

    public function __construct()
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
        $this->paused = false;
        $this->finished = false;
        $this->number = 20;
        $this->note = 7;
        $this->scores = new ArrayCollection();
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
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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
    public function setUser(User $user)
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
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    protected function onPreUpdate() {
        $this->updatedAt = new \DateTime();
    }
}
