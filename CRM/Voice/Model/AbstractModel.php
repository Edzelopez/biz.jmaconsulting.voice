<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/23/14
 * Time: 11:52 AM
 */

namespace CRM\Voice\Model;


class AbstractModel
{
    protected  $dao;

    protected function setDao(&$dao)
    {
        $this->dao = $dao;
    }
}