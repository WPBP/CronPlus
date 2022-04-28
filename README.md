# CronPlus
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![Downloads](https://img.shields.io/packagist/dt/wpbp/cronplus.svg) 

Add and remove Cron job in WordPress easily!

## Install

`composer require wpbp/cronplus:dev-master`

[composer-php52](https://github.com/composer-php52/composer-php52) supported.

## Example

```php
$args = array(
    // to execute at a specific time based on recurrence
    'time' => time(), // not mandatory, will use the current time
    // hourly, daily, twicedaily, weekly, monthly or timestamp for single event
    'recurrence' => 'hourly',
    // schedule (specific interval) or single (at the time specified)
    'schedule' => 'schedule',
    // Name of the Cron job used internally
    'name' => 'cronplusexample',
    // Callback to execute when the cron job is launched
    'cb' => 'cronplus_example',
    // Multisite support disabled by default
    'multisite'=> false,
    // Used on deactivation for register_deactivation_hook to cleanup
    'plugin_root_file'=> '',
    // When the event is scheduled is also executed 
    'run_on_creation'=> false,
    // Args passed to the hook executed during the cron
    'args' => array( get_the_ID() )
);

function cronplus_example( $id ) {
	echo $id;
}

$cronplus = new CronPlus( $args );
// Schedule the event
$cronplus->schedule_event();
// Remove the event by the schedule
$cronplus->clear_schedule_by_hook();
// Jump the scheduled event
$cronplus->unschedule_specific_event();
```
