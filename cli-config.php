<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/24/14
 * Time: 10:45 AM
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once __DIR__ . '/bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
//$entityManager = GetEntityManager();

return ConsoleRunner::createHelperSet($entityManager);