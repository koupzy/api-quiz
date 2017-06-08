<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 08/06/17
 * Time: 10:01
 */

namespace AppBundle\Model;

use AppBundle\Entity\Quiz;

/**
 * Class QuizManager
 * @package AppBundle\Model
 */
abstract class QuizManager implements QuizManagerInterface
{
    public function create()
    {
        // TODO: Implement create() method.
        $quiz = new Quiz();
    }

    public function start()
    {
        // TODO: Implement start() method.
    }

    public function pause()
    {
        // TODO: Implement pause() method.
    }


}