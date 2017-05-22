<?php
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Quiz as Quiz;

/**
 * Class User
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 *
 * @author Joel
 */
class User
{
    /**
     * @var integer
     * @ORM\Column(type="integer",options={"unsigned= true"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $lastName
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @var string $firstName
     */
    private $firstName;

    /**
     * @var string $username
     * @ORM\Column(type="string",length=255, unique=true)
     */
    private $username;

    /**
     * @var string $password
     * @ORM\Column(type="string",length=255)
     */
    private $password;

    /**
     * @var Collection Quiz
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $quizs;

    /**
     * User constructor.
     * @internal param \AppBundle\Entity\Quiz $quizs
     */
    public function __construct()
    {
        $this->quizs = new ArrayCollection();
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
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }



    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return \AppBundle\Entity\Quiz
     */
    public function getQuizs()
    {
        return $this->quizs;
    }

    /**
     * @param \AppBundle\Entity\Quiz $quizs
     * @return $this
     */
    public function addQuizs(Quiz $quizs)
    {
        if (false === $this->quizs->contains($quizs))
        {
            $this->quizs->add($quizs);
        }

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Quiz $quiz
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

    public function setQuizs(ArrayCollection $quizs){

        $this->quizs = new ArrayCollection();
        foreach ($quizs as $quiz){
            $this->addQuizs($quiz);
        }
        return $this;
    }


}