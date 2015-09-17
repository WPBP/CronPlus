<?php

/**
 * Plugin Name: CronPlus
 * Plugin URI: https://github.com/Mte90/cronplus
 * Description: Facilitates the creation of cron events .
 * Author: Mte90 &  brutalenemy666
 * Author URI: http://mte90.net
 * Version: 1.0.0
 * Text Domain: your-domain
 * Domain Path: /languages
 */
if ( !defined( 'WPINC' ) ) {
	die;
}

// Load and initialize class. If you're loading the CronPlus class in another plugin or theme, this is all you need.
require_once 'class-cronplus.php';
$args = array( 'recurrence' => 'hourly', 'schedule' => 'schedule', 'name' => 'cronplusexample', 'cb' => cronplus_example() );

function cronplus_example() {
	echo 123;
}

$cronplus = new CronPlus( $args );
$cronplus->schedule_event();
