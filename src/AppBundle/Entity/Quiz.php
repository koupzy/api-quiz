<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 19/05/17
 * Time: 09:36
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;


class Quiz
{
    /**
     * @var integer
     * @ORM\Column(name="id_quiz")
     * @ORM\Id
     */
    private $id;

    /**
     * @var date
     * @ORM\Column(name="date_creat",type="datetime")
     */
    private $dateCreat;

    /**
     * @var string
     * @ORM\Column(name="state",type="string",length=255)
     */
    private $state;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="quizs")
     * @ORM\JoinColumn(name="id_quiz",referencedColumnName="id_user")
     */
    private $user;

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
     * @return Date
     */
    public function getDateCreat()
    {
        return $this->dateCreat;
    }

    /**
     * @param Date $dateCreat
     */
    public function setDateCreat($dateCreat)
    {
        $this->dateCreat = $dateCreat;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
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
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}