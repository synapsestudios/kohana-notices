<?php defined('SYSPATH') OR die('No direct access allowed.');

Route::set('notice-add', 'notice/add/<type>/<message>(/<persist>)')
	->defaults(array(
		'controller' => 'notice',
		'action'     => 'add',
		'persist'    => FALSE
	));

Route::set('notice-remove', 'notice/remove/<hash>')
	->defaults(array(
		'controller' => 'notice',
		'action'     => 'remove'
	));


Notices::init();