<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/23/14
 * Time: 11:46 AM
 */

namespace CRM\Voice\Model;


class Voice extends AbstractModel
{

    public function __construct($dao = null)
    {
        if ($dao) {
            $this->setDao($dao);
        } else {
            $this->setDao(new CRM\Voice\DAO\Voice());
        }
    }
} 