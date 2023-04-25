<?php

/**
 * @package   CronPlus
 * @author    Mte90
 * @license   GPL-3.0+
 * @link      http://mte90.net
 * @copyright 2015-2018 GPL
 */

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
			'time' => current_time('timestamp', true), // If you want a specific time (unix timestamp required)
			'recurrence' => 'hourly', // Hourly,daily,twicedaily,weekly,monthly
			'name' => 'cronplus',
			'schedule' => 'schedule', // Schedule or single,
			'cb' => '',
			'multisite' => false,
			'plugin_root_file' => '',
			'run_on_creation' => false,
			'args' => array( '' ) // Args passed to the hook
		);

		$this->args = wp_parse_args( $args, $defaults );
		if ( isset( $this->args[ 'cb' ] ) && isset( $this->args[ 'name' ] ) ) {
			add_action( $this->args[ 'name' ], $this->args[ 'cb' ] );
		}
		if ( !empty( $this->args[ 'plugin_root_file' ] ) ) {
			register_deactivation_hook( $this->args[ 'plugin_root_file' ], array( $this, 'deactivate' ) );
		}
	}

	/**
	 * Schedule the event
	 *
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function schedule_event() {
		if ( $this->is_scheduled( $this->args[ 'name' ] ) ) {
			return;
		}
		if ( $this->args[ 'run_on_creation' ] ) {
			call_user_func( $this->args[ 'cb' ], $this->args[ 'args' ] );
		}
		if ( $this->args[ 'schedule' ] === 'schedule' ) {
			wp_schedule_event( $this->args[ 'time' ], $this->args[ 'recurrence' ], $this->args[ 'name' ], $this->args[ 'args' ] );
		} elseif ( $this->args[ 'schedule' ] === 'single' ) {
			wp_schedule_single_event( $this->args[ 'recurrence' ], $this->args[ 'name' ], $this->args[ 'args' ] );
		}

		// Save all the site ids where is the corn for the deactivation
		if ( is_multisite() && !wp_is_large_network() ) {
			$sites = ( array ) get_site_option( $this->args[ 'name' ] . '_sites', array() );
			$sites[] = get_current_blog_id();
			update_site_option( $this->args[ 'name' ] . '_sites', $sites );
		}

		return true;
	}

	/**
	 * Clear the schedule
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function clear_schedule_by_hook() {
		wp_clear_scheduled_hook( $this->args[ 'name' ], $this->args[ 'args' ] );
	}

	/**
	 * UnSchedule the event
	 *
	 * @since    1.0.0
	 * 
	 * @param number $timestamp The timestamp to remove
	 * 
	 * @return void
	 */
	public function unschedule_specific_event( $timestamp = '' ) {
		if ( empty( $timestamp ) ) {
			$timestamp = wp_next_scheduled( $this->args[ 'name' ], $this->args[ 'args' ] );
		}
		wp_unschedule_event( $timestamp, $this->args[ 'name' ], $this->args[ 'args' ] );
	}

	/**
	 * Remove all the cron on deactivation
	 * 
	 * @return void
	 */
	public function deactivate() {
		$this->clear_schedule();
		if ( !is_multisite() || wp_is_large_network() ) {
			return;
		}

		$sites = ( array ) get_site_option( $this->args[ 'name' ] . '_sites', array() );

		$sites and $sites = array_diff( $sites, [ get_current_blog_id() ] );

		foreach ( $sites as $site ) {
			switch_to_blog( $site );
			$this->clear_schedule();
		}
		restore_current_blog();

		delete_site_option( $this->args[ 'name' ] . '_sites' );
	}

	/**
	 * Check if the event is scheduled
	 * 
	 * @param string $name
	 * @param array $crons
	 * @return bool
	 */
	private function is_scheduled( $name ) {
		$crons = _get_cron_array();
		
		if ( empty( $crons ) ) {
			return false;
		}

		foreach ( $crons as $cron ) {
			if ( isset( $cron[ $name ] ) ) {
				return true;
			}
		}

		return false;
	}

}
