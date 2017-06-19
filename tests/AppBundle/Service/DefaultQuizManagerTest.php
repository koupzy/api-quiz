<?php
namespace Tests\AppBundle\Service;

use AppBundle\Entity\Quiz;
use AppBundle\Model\QuizManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 *
 * @author Joel
 *
 */
class DefaultQuizManagerTest extends kernelTestCase
{
    /**
     * @var EntityManagerInterface $em
     */
    protected $em = null;

    /**
     * @var  QuizManagerInterface $quizManager
     */
    protected $quizManager = null;

    public function setUp()
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();

        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->quizManager = $container->get('app.default_quiz_manager');
    }

    /**
     * @return integer $id
     */
    public function testCreate() {
        $user = $this->em->getRepository('AppBundle:User')->find(1);
        $quiz = $this->quizManager->create($user);

        $this->assertGreaterThan(0, $quiz->getId());
        $this->assertEquals(true, $this->em->contains($quiz));

        return $quiz->getId();
    }

    /**
     * @param integer $id
     * @depends testCreate
     */
    public function testStart($id)
    {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find($id);
        $this->quizManager->start($quiz);

        $this->assertGreaterThan(0, $quiz->getScores()->count());
    }

    /**
     * @param $id
     * @depends testCreate
     */
    public function testDelivery($id)
    {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find($id);
        $question = $this->quizManager->delivery($quiz);
        $this->assertGreaterThan(0,$question->getId());
    }

    /**
     * @param $id
     * @depends testCreate
     */
    public function testPause($id)
    {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find($id);
        $this->quizManager->pause($quiz);
        $this->assertEquals(true,$quiz->isPaused());
    }

    /**
     * @param $id
     * @depends testCreate
     */
    public function testStop($id)
    {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find($id);
        $this->quizManager->stop($quiz);
        $this->assertEquals(true,$quiz->isfinished());
    }

    /**
     * @param $id
     * @depends testCreate
     */
    public function testResume($id)
    {
        $quiz = $this->em->getRepository('AppBundle:Quiz')->find($id);
        $this->quizManager->resume($quiz);
        $this->assertEquals(false,$quiz->isPaused());
    }
}