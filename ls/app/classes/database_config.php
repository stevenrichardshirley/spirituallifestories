<?php
class DATABASE_CONFIG {
    // if/when we have a distributed db model, master is our writeable db.
    var $master = array(
        'driver' => 'mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'admin_ls',
        'password' => '@@LifeStories2016!!',
        'database' => 'admin_ls',
        'prefix' => '',
    );

    // test is our development db.
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'admin_ls',
		'password' => '@@LifeStories2016!!',
		'database' => 'admin_ls',
		'prefix' => '',
	);
}
?>
