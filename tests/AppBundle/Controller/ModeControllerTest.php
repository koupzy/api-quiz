<?php
namespace Tests\AppBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ModeControllerTest
 * @package Tests\AppBundle\Controller
 * @author Ange Paterson
 */
class ModeControllerTest extends TemplateTest
{

    public function setUp()
    {
        parent::setUp();
    }


    function testCreate()
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_mode_create', [], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json'
            ],
            '{"label":"Mode00"}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Mode00', $content['label']);

        return $content;
    }

    /**
     * @param array $mode
     * @return mixed
     * @depends testCreate
     */
    function testRead(array $mode)
    {
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_mode_read', ['id'=>$mode['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Mode00', $content['label']);

        return $content;
    }

    /**
     * @param array $mode
     * @depends testRead
     * @return mixed
     */
    function testUpdate(array $mode)
    {
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_mode_update', ['id'=>$mode['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            [
                'HTTP_Content-Type' => 'application/json'
            ],
            '{"label":"Mode00"}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Mode00', $content['label']);

        return $content;
    }

    /**
     * @param array $mode
     * @depends testUpdate
     */
    function testDelete(array $mode)
    {
        $crawler = $this->client->request(
          'DELETE',
          $this->router->generate('api_quiz_mode_delete', ['id' => $mode['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
          [],
          [],
          ['HTTP_Content-Type' => 'application/json']
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(204, $response->getStatusCode());
    }
}