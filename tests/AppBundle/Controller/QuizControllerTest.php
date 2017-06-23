<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 21/06/17
 * Time: 09:59
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QuizControllerTest extends WebTestCase
{
    /** @var  Request $client */
    protected $client;

    /** @var Router $router */
    protected $router;

    public function setUp(){

        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    public function testCreate(){

        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_quiz_create', ['id'=>1], UrlGeneratorInterface::ABSOLUTE_PATH),
            ['id'=>1],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{"number":5 }'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());


        return $content;
    }

    /**
     * @param array $quiz
     * @return mixed
     * @internal param array $userId
     * @depends testCreate
     */
    public function testRead(array $quiz){
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_quiz_read', ['userId'=>1, 'id'=>$quiz['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());

        return $content;
    }
}