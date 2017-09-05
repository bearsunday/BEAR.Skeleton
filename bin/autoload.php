<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

require dirname(__DIR__) . '/vendor/autoload.php';
AnnotationRegistry::registerLoader('class_exists');
