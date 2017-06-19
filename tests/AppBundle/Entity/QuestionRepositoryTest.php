<?php
namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Quiz;
use AppBundle\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    /**
     * @var QuestionRepository $er
     */
    protected $er;


    public function setUp() {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->er = $this->em->getRepository('AppBundle:Question');
    }

    public function testFindForQuiz() {
        $quizs = $this->er->findForQuiz(10);
        $this->assertGreaterThan(0, count($quizs));
    }

    public function testFindOneByScore() {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find(1);
        $question = $this->er->findOneByQuiz($quiz);
        $this->assertEquals(1, $question->getId());
    }
}