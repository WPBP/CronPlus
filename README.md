## CronPlus
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)   

Add and remove Cron job in WordPress easily!

## Example

```
$args = array(
    // hourly,daily,twicedaily,weekly,monthly or timestamp for single event
    'recurrence' => 'hourly',
    // schedule (specific interval) or single (at the time specified)
    'schedule' => 'schedule',
    // Name of the Cron job used internally
    'name' => 'cronplusexample',
    // Callback to execute when the cron job is launched
    'cb' => 'cronplus_example'
);

function cronplus_example() {
	echo 123;
}

$cronplus = new CronPlus( $args );
$cronplus->schedule_event();
$cronplus->clear_schedule();
$cronplus->unschedule_event();
```