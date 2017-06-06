<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 06/06/17
 * Time: 15:49
 */

/**
 * Class ControllerTest
 * @package Tests\AppBundle
 */
abstract class ControllerTest extends WebTestCase{

    public static function setUp(){

        $client = static::createClient();
    }

    abstract function testCreate();

    abstract function testRead(array $id);

    abstract function testUpdate(array $id);

    abstract function testDelete(array $id);

}