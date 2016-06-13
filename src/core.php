<?php

use App\CatManager;

require __DIR__ . '/../vendor/autoload.php';

CatManager::getInstance()->loadCats('cats.json');

$view = array(
    'dates' => CatManager::getInstance()->renderDates(),
    'tree' => CatManager::getInstance()->renderTree(),
    'w' => CatManager::getInstance()->maxX,
    'h' => CatManager::getInstance()->maxY
);