<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 21/05/17
 * Time: 13:30
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Answer
 * @package AppBundle\Entity
 * @ORM\Table(name="answer")
 * @ORM\Entity()
 */
class Answer
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="answer_text", type="string")
     */
    protected $answerText;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * @param mixed $answerText
     */
    public function setAnswerText($answerText)
    {
        $this->answerText = $answerText;
    }
}