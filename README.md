## CronPlus
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)   

Add and remove Cron job in WordPress easily!

## Example

```
$args = array(
    'recurrence' => 'hourly',
    'schedule' => 'schedule',
    'name' => 'cronplusexample',
    'cb' => 'cronplus_example'
);

function cronplus_example() {
	echo 123;
}

$cronplus = new CronPlus( $args );
$cronplus->schedule_event();
```