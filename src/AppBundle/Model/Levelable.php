<?php
namespace AppBundle\Model;

use AppBundle\Entity\Level;

trait Levelable
{
    /**
     * @return Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param Level $level
     * @return $this
     */
    public function setLevel(Level $level)
    {
        $this->level = $level;
        return $this;
    }

}