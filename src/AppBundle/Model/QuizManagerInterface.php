<?php
namespace AppBundle\Model;

use AppBundle\Entity\Category;
use AppBundle\Entity\Mode;
use AppBundle\Entity\User;
use Symfony\Component\BrowserKit\Request;

/**
 * Interface QuizManagerInterface
 * @package AppBundle\Model
 */
interface QuizManagerInterface{

    public function create(Request $request, User $user, Mode $mode, Category $category);

    public function start();

    public function stop();

    public function pause();

    public function resume();

    public function showTime();

    public function mark();
}