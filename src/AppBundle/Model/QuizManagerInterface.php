<?php
namespace AppBundle\Model;

/**
 * Interface QuizManagerInterface
 * @package AppBundle\Model
 */
interface QuizManagerInterface{

    public function create();

    public function start();

    public function stop();

    public function pause();

    public function resume();

    public function showTime();

    public function mark();
}