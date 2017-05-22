<?php
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Quiz as Quiz;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="user")
 *
 * @author Joel
 */
class User
{
    /**
     * @var integer
     * @ORM\Column(name="id_user",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nom;

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     * @var string $prenom
     */
    private $prenom;

    /**
     * @var string $username
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $username;

    /**
     * @var string $password
     * @ORM\Column(type="string",length=255)
     */
    private $password;

    /**
     * @var Quiz
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz",mappedBy="user")
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return $this
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return $this
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
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
        $this->quizs[] = $quizs;
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Quiz $quiz
     */
    public function removeQuizs(Quiz $quiz)
    {
        $this->quizs->removeElement($quiz);
    }


}