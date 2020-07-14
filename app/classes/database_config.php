<?php
class DATABASE_CONFIG {
    // if/when we have a distributed db model, master is our writeable db.
	var $master = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'sls_sqladmin',
		'password' => '67&4a3.,o9724D',
		'database' => 'sls_summit',
		'prefix' => '',
	);

    // test is our development db.
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'SLS',
		'password' => '87983@#)(JD_@',
		'database' => 'SLS',
		'prefix' => '',
	);
}
?>
