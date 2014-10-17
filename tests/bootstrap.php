<?php

require dirname(__DIR__) . '/vendor/cakephp/cakephp/src/basics.php';
require dirname(__DIR__) . '/vendor/autoload.php';

define('PLUGIN_REPO_ROOT', dirname(__DIR__) . DS);

Cake\Core\Plugin::load(
	'TwigView',
	[
		'namespace' => 'WyriHaximus\\CakePHP\FlyPie',
		'path' => PLUGIN_REPO_ROOT . 'src' . DS,
	]
);
