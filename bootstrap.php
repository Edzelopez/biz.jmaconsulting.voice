<?php

// bootstrap.php
require_once  __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/DB_Settings.php';


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$paths      = array(__DIR__ . '/CRM/Voice/Entities');
$isDevMode  = true;
$dbSetting  = new DB_Settings();

$config = Setup::createConfiguration($isDevMode);
$driver = new AnnotationDriver(new AnnotationReader(), $paths);

// registering noop annotation autoloader - allow all annotations by default
AnnotationRegistry::registerLoader('class_exists');
$config->setMetadataDriverImpl($driver);

//Creating Entity Manager
$entityManager = EntityManager::create($dbSetting->toDoctrineArray(), $config);


//Setting up Enu, type
$conn = $entityManager->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum','string');

//Return the whole entity manager
return $entityManager;