<?php
namespace Tests\AppBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 * @author Ange Paterson
 */
class UserControllerTest extends TemplateTest
{
    public function setUp()
    {
        parent::setUp();
    }


    public function testCreate()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_user_create', [], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{
            "lastName":"Kone",
            "firstName":"Ange",
            "userName":"Paterson03",
            "password":"test"
            }'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());


        return $content;

    }

    /**
     * @param array $user
     * @depends testCreate
     * @return $this
     */
    function testRead(array $user)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_user_read', ['id' => $user['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());


        return $content;
    }

    /**
     * @param array $user
     * @depends testRead
     * @return $this
     */
    function testUpdate(array $user)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_user_update', ['id' => $user['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{
            "lastName":"Kone",
            "firstName":"Ange",
            "userName":"Paterson03",
            "password":"test"
            }'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());


        return $content;

    }

    /**
     * @param array $user
     * @depends testUpdate
     */
    function testDelete(array $user)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'DELETE',
            $this->router->generate('api_quiz_user_delete', ['id' => $user['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(204, $response->getStatusCode());
    }


}