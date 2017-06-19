<?php
namespace AppBundle\Model;

use AppBundle\Entity\Category;
use AppBundle\Entity\Level;
use AppBundle\Entity\Mode;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;

/**
 * Interface QuizManagerInterface
 * @package AppBundle\Model
 */
interface QuizManagerInterface{


    public function create(User $user,Category $category = null,int $nombre = null,Level $level = null,Mode $mode = null,bool $andFlush = true);


    public function start(Quiz $quiz);

    public function delivery(Quiz $quiz);

    public function stop(Quiz $quiz);

    public function pause(Quiz $quiz);

    public function resume(Quiz $quiz);

    public function showTime();

    public function mark();
}