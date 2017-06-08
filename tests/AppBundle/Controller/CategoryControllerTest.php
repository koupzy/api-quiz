<?php
namespace Tests\AppBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class CategoryControllerTest
 * @package Tests\AppBundle\Controller
 * @author Ange Paterson
 */
class CategoryControllerTest extends TemplateTest
{

    public function setUp()
    {
        parent::setUp();
    }

    function testCreate()
    {
        $crawler = $this->client->request(
            'POST',
            $this->router->generate('api_quiz_category_create', [], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{"name":"VEONE"}'
        );

        /** @var Response $response */
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('VEONE', $content['name']);

        return $content;
    }

    /**
     * @param array $category
     * @return mixed
     * @depends testCreate
     */
    function testRead(array $category)
    {
        $crawler = $this->client->request(
            'GET',
            $this->router->generate('api_quiz_category_read', ['id' => $category['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('VEONE', $content['name']);

        return $content;
    }

    /**
     * @param array $category
     * @depends testRead
     */
    function testUpdate(array $category)
    {
        $crawler = $this->client->request(
            'PUT',
            $this->router->generate('api_quiz_category_update', ['id' => $category['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            '{"name":"VEONE"}'
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('VEONE', $content['name']);

        return $content;
    }

    /**
     * @param array $category
     * @depends testUpdate
     */
    function testDelete(array $category)
    {
        $crawler = $this->client->request(
            'DELETE',
            $this->router->generate('api_quiz_category_delete', ['id' => $category['id']], UrlGeneratorInterface::ABSOLUTE_PATH),
            [],
            [],
            []
        );

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(204, $response->getStatusCode());
       // $this->assertEquals('VEONE', $content['name']);
        //'HTTP_Content-Type' => 'application/json'
    }
}
