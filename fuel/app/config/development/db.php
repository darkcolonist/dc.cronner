<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'pgsql:host=localhost;dbname=dc_deploy',
			'dsn'        => 'sqlite:'.APPPATH.'../../db/dc_fuse.db',
			'username'   => 'dev',
			'password'   => 'dev',
		),
    'charset'     => null,
    'profiling'   => true
	),
);
