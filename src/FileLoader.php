<?php
namespace Symbiotic;

class FileLoader {

	/**
	 * @param $files
	 */
	public static function load( $files ) {
		if ( is_array( $files ) ) {
			array_walk( $files, function ( $file ) {
				self::include_file( $file );
			} );
		} else {
			self::include_file( $files );
		}
	}

	/**
	 * @param $file
	 */
	private static function include_file( $file ) {
		if (!locate_template($file, true, true)) {
			Utils::sym_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'symbiotic'), $file), 'File not found');
		}
	}

	/**
	 * @param $files
	 * @param $class_exists
	 * @param string $func
	 */
	public static function loadIfExists( $files, $class_exists, $func = 'class_exists' ) {
		if ( $func( $class_exists ) ) {
			self::load( $files );
		}
	}

	/**
	 * @param $files
	 * @param $class_exists
	 */
	public static function loadIfNotExists( $files, $class_exists ) {
		if ( ! class_exists( $class_exists ) ) {
			self::load( $files );
		}
	}


}