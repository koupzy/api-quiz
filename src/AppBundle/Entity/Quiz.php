<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 19/05/17
 * Time: 09:36
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Quiz
 * @ORM\Table(name="quiz")
 * @ORM\Entity()
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
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="quizs", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->paused == false;
        $this->finished == false;
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






}