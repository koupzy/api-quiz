<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 08/06/17
 * Time: 18:17
 */

namespace AppBundle\Model;


trait levelable
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