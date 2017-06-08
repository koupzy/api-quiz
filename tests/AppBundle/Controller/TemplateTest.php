<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;

/**
 * Class ControllerTest
 * @package Tests\AppBundle
 * @author Ange Paterson
 */
abstract class TemplateTest extends WebTestCase{

    /** @var  Request $client */
    protected $client;

    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
    protected $router;

    public function setUp(){

        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    abstract function testCreate();

    abstract function testRead(array $id);

    abstract function testUpdate(array $id);

    abstract function testDelete(array $id);

}