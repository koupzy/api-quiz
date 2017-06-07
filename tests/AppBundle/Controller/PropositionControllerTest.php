<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 07/06/17
 * Time: 09:59
 */

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PropositionControllerTest extends WebTestCase
{
    /** @var  Request $client */
    protected $client;

    /** @var  \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
    protected $router;

    public function setUp() {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    /**
     * @return mixed
     * @internal param Request $request
     */
    public function testCreate() {
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_proposition_create',[],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json'
            ],
             '{"content":"propositionTest", "truth":true,  "point":2}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);
        $this->assertEquals(201,$response->getStatusCode());
        $this->assertEquals('propositionTest',$content['content']);
        $this->assertEquals(true,$content['truth']);
        $this->assertEquals(2,$content['point']);

        return $content;
    }

    /**
     * @param array $question
     * @return mixed
     * @depends testCreate
     */
    public  function testRead(array $question) {
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_proposition_read',['id' => $question['id']],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals('propositionTest',$content['content']);
        $this->assertEquals(true,$content['truth']);
        $this->assertEquals(2,$content['point']);

        return $content;
    }

    /**
     * @param array $question
     * @return mixed
     * @depends testRead
     */
    public function testUpdate(array $question) {
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_proposition_update',['id' => $question['id']],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json'
            ],
            '{"content":"propositionTest", "truth":true,  "point":2}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);
        $this->assertEquals(201,$response->getStatusCode());
        $this->assertEquals('propositionTest',$content['content']);
        $this->assertEquals(true,$content['truth']);
        $this->assertEquals(2,$content['point']);

        return $content;
    }

    /**
     * @param array $question
     * @depends testUpdate
     */
    public function testDelete(array $question) {
        $crawler = $this->client->request(
            'DELETE',
            $this->router->generate('api_quiz_proposition_delete',['id' => $question['id']],UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json']
        );
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(),true);
        $this->assertEquals(204, $response->getStatusCode());
    }

}