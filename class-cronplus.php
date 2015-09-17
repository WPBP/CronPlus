<?php

/**
 * Class CronPlus to facilitate creation of Cron Events
 * 
 * @package   CronPlus
 * @author    Mte90
 * @license   GPL-2.0+
 * @link      http://mte90.net
 * @copyright 2014 GPL
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class CronPlus {

	private $args;

	/**
	 * @param array $args
	 */
	function __construct( $args ) {
		$defaults = array(
		    'recurrence' => 'hourly', //hourly,daily,twicedaily,weekly,monthly
		    'name' => 'cronplus',
		    'schedule' => 'schedule' //schedule or single
		);

		$this->args = wp_parse_args( $args, $defaults );
		add_action( $this->args[ 'name' ], $this->cb );
	}

	public static function schedule_event() {
		if ( !wp_next_scheduled( $this->args[ 'name' ] ) ) {
			if ( $this->args[ 'schedule' ] ) {
				wp_schedule_event( current_time( 'timestamp' ), $this->args[ 'recurrence' ], $this->args[ 'name' ] );
			} else {
				wp_schedule_single_event( $this->args[ 'recurrence' ], $this->args[ 'name' ] );
			}
		}
	}

	public function clear_schedule() {
		wp_clear_scheduled_hook( $this->args[ 'name' ] );
	}

	public function unschedule_event() {
		$timestamp = wp_next_scheduled( $this->args[ 'name' ] );
		wp_unschedule_event( $timestamp, $this->args[ 'name' ] );
	}

}
