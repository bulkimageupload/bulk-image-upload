<?php
/**
 * This class provides functionality to help with visual representation of statuses.
 *
 * @package bulk-image-upload-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The class to help with status colors.
 */
class Bulk_Image_Upload_Status_Color {

	const JOB_STATUS_PENDING               = 'pending';
	const JOB_STATUS_PROCESSING            = 'processing';
	const JOB_STATUS_COMPLETED             = 'completed';
	const JOB_STATUS_COMPLETED_WITH_ERRORS = 'completed_with_errors';
	const JOB_STATUS_FAILED                = 'failed';

	/**
	 * Mapping of statuses to color codes
	 *
	 * @var string[]
	 */
	private static $colors_by_statuses = array(
		self::JOB_STATUS_FAILED                => '#ffabaf',
		self::JOB_STATUS_COMPLETED             => '#68de7c',
		self::JOB_STATUS_PROCESSING            => '#c5d9ed',
		self::JOB_STATUS_PENDING               => '#f5e6ab',
		self::JOB_STATUS_COMPLETED_WITH_ERRORS => '#b8e6bf',
	);

	/**
	 * The method returns hex color code by given status.
	 *
	 * @param string $status Upload job statuses.
	 *
	 * @return string
	 */
	public static function get_color_by_status( $status ) {
		if ( ! empty( self::$colors_by_statuses[ $status ] ) ) {
			return self::$colors_by_statuses[ $status ];
		}

		return '#f0f0f1';
	}
}
