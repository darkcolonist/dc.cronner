<?php
require_once(dirname(__FILE__) . '/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
Doctrine_Core::loadModels(APPPATH.'classes/model');

Config::load("db",true);
$config = Config::get("db");

$dsn = $config["default"]["connection"]["dsn"];
$user = $config["default"]["connection"]["username"];
$password = $config["default"]["connection"]["password"];

$dbh = new PDO($dsn, $user, $password);

$conn = Doctrine_Manager::connection($dbh);
$conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);