<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 23/06/17
 * Time: 10:07
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QuizControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    public function testDelivery()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_quiz_delivery', ['quizId'=> 28], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
            );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $content['multipleChoice']);
        $this->assertEquals(2, $content['duration']);

    }

    /**
     * @return array $content
     */
    public function testPause()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_quiz_pause', ['quizId'=> 27], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $content['paused']);
        $this->assertEquals(7, $content['note']);

        return $content;
    }

    /**
     * @param array $quiz
     * @return mixed
     * @depends testPause
     */
    public function testResume(array $quiz)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_quiz_resume', ['quizId'=> $quiz['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(false, $content['paused']);
        $this->assertEquals(7, $content['note']);

        return $content;

    }

    /**
     * @param array $quiz
     * @depends testPause
     */
    public function testStop(array $quiz)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_quiz_stop', ['quizId'=> $quiz['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $content['finished']);
        $this->assertEquals(7, $content['note']);

    }

}