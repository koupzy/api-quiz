<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QuestionControllerTest extends WebTestCase
{
    /**
     * @var Request $client
     */
    protected $client;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    protected $router;

    public function setUp() {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    public function testCreate(){
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_question_create', [], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json'
            ],
            '{"content":"contentTest2", "duration":2,  "multipleChoice":true}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('contentTest2', $content['content']);
        $this->assertEquals(true, $content['multipleChoice']);
        $this->assertEquals(2, $content['duration']);

        return $content;
    }

    /**
     * @depends testCreate
     */
    public function testRead(array $question) {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_question_read', ['id' => $question['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('contentTest2', $content['content']);
        $this->assertEquals(true, $content['multipleChoice']);
        $this->assertEquals(2, $content['duration']);

        return $content;
    }

    /**
     * @param array $question
     * @depends testRead
     * @return mixed
     */
    public function testUpdate(array $question) {
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_question_update',['id' => $question['id']],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{"content":"contentTest2", "duration":2,  "multipleChoice":false}'
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('contentTest2', $content['content']);
        $this->assertEquals(false, $content['multipleChoice']);
        $this->assertEquals(2, $content['duration']);

        return $content;
    }

    /**
     * @param array $question
     * @depends testUpdate
     */
    public function testDelete(array $question) {
        $crawler = $this->client->request(
            'DELETE',
            $this->router->generate('api_quiz_question_delete',['id' => $question['id']],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json']
        );
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);
        $this->assertEquals(204,$response->getStatusCode());
    }
}