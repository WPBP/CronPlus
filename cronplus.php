<?php

/**
 * @package   CronPlus
 * @author    Mte90
 * @license   GPL-3.0+
 * @link      http://mte90.net
 * @copyright 2015-2016 GPL
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  * Add and remove Cron job in WordPress easily!
 */
class CronPlus {

	/**
	 * Args of the class
	 *
	 * @var     array
	 * @since   1.0.0
	 */
	private $args;

	/**
	 * Construct the class parameter
	 * 
	 * @param array $args Parameters of class.
	 * @return void
	 */
	function __construct( $args ) {
		$defaults = array(
			'recurrence' => 'hourly', // Hourly,daily,twicedaily,weekly,monthly
			'name' => 'cronplus',
			'schedule' => 'schedule', // Schedule or single,
			'cb' => '',
			'args' => array( '' ) // Args passed to the hook
		);

		$this->args = wp_parse_args( $args, $defaults );
		if ( isset( $this->args[ 'cb' ] ) && isset( $this->args[ 'name' ] ) ) {
			add_action( $this->args[ 'name' ], $this->args[ 'cb' ] );
		}
	}

	/**
	 * Schedule the event
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function schedule_event() {
		$find = false;
		$crons = _get_cron_array();
		foreach ( $crons as $timestamp => $cron ) {
			if ( isset( $cron[ $this->args[ 'name' ]] ) ) {
				$find = true;
			}
		}
		if ( !$find && !wp_next_scheduled( $this->args[ 'name' ] ) ) {
			if ( $this->args[ 'schedule' ] === 'schedule' ) {
				wp_schedule_event( current_time( 'timestamp' ), $this->args[ 'recurrence' ], $this->args[ 'name' ], $this->args[ 'args' ] );
			} elseif ( $this->args[ 'schedule' ] === 'single' ) {
				wp_schedule_single_event( $this->args[ 'recurrence' ], $this->args[ 'name' ], $this->args[ 'args' ] );
			}
		}
	}

	/**
	 * Clear the schedule
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function clear_schedule() {
		wp_clear_scheduled_hook( $this->args[ 'name' ] );
	}

	/**
	 * UnSchedule the event
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function unschedule_event() {
		$timestamp = wp_next_scheduled( $this->args[ 'name' ] );
		wp_unschedule_event( $timestamp, $this->args[ 'name' ] );
	}

}
