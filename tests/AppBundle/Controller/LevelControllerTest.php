<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 27/06/17
 * Time: 12:02
 */

namespace Tests\AppBundle\Controller;


use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LevelControllerTest
 * @package Tests\AppBundle\Controller
 * @author Ange Paterson
 */
class LevelControllerTest extends TemplateTest
{
    public function setUp()
    {
        parent::setUp();
    }

    function testCreate()
    {
       $crawler = $this->client->request(
           'POST',
           $this->router->generate('api_quiz_level_list', [], UrlGeneratorInterface::ABSOLUTE_PATH),
           [],
           [],
           ['HTTP_Content-Type' => 'application/json',],
            '{"label" : "hard"}'
       );
        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('hard', $content['label']);

        return $content;
    }

    /**
     * @param array $level
     * @return mixed
     * @depends testCreate
     */
    function testRead(array $level)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_level_list', ['id' => $level['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        //$this->assertEquals('hard', $content['label']);

        return $content;
    }

    /**
     * @param array $level
     * @return mixed
     * @depends testRead
     */
    function testUpdate(array $level)
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_level_update', ['id' => 5], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{"label":"hards"}'
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('hards', $content['label']);

        return $content;
    }

    /**
     * @param array $level
     * @depends testUpdate
     */
    function testDelete(array $level)
    {
        $crawler = $this->client->request(
            'DELETE',
            $this->router->generate('api_quiz_level_delete', ['id' => $level['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(204, $response->getStatusCode());
    }

}