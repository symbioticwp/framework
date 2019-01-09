<?php
namespace Symbiotic;
/**
 * Class Utils
 * @package Mj
 */
class Utils {

	/**
	 * @param $attributes
	 *
	 * @return string
	 */
	public static function attrToArray( $attributes ) {
		return join( ' ', array_map( function ( $key ) use ( $attributes ) {
			if ( is_bool( $attributes[ $key ] ) ) {
				return $attributes[ $key ] ? $key : '';
			}

			return $key . '="' . $attributes[ $key ] . '"';
		}, array_keys( $attributes ) ) );
	}

	/**
	 * @param $attr
	 * @param $arr
	 *
	 * @return string
	 */
	public static function toAttr( $attr, $arr ) {
		return self::attrToArray( [ $attr => join( ' ', array_map( 'esc_attr', $arr ) ) ] );
	}

	/**
	 * @param $size
	 *
	 * @return string
	 */
	public static function format_size( $size ) {
		$sizes = array( " Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB" );
		if ( $size == 0 ) {
			return ( 'n/a' );
		} else {
			return ( round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . $sizes[ $i ] );
		}
	}

	/**
	 * Get items by post settings
	 */
	public static function mj_get_post_items_by_settings( $settings, $post_type = 'post' ) {

		if ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} elseif ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else {
			$paged = 1;
		}

		$meta_query['relation'] = self::getIfSet( $settings['meta_query_relation'] ) ? $settings['meta_query_relation'] : '';

		// Portfolio category
		$tax_query = '';
		if ( $post_type == 'portfolio' && ! empty( $settings['category'] ) ) {
			$tax_query            = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field'    => 'slug',
					'terms'    => explode( ',', $settings['category'] ),
				),
			);
			$settings['category'] = '';
		}

		$post_args = array(
			'posts_per_page' => self::getIfSet( $settings['posts_per_page'] ) ? $settings['posts_per_page'] : - 1,
			'offset'         => self::getIfSet( $settings['offset'] ) ? $settings['offset'] : 0,
			'category_name'  => self::getIfSet( $settings['category'] ) ? $settings['category'] : '',
			'post__in'       => ! empty( trim( self::getIfSet( $settings['include'] ) ) ) ? explode( ',', $settings['include'] ) : '',
			'post__not_in'   => explode( ',', self::getIfSet( $settings['exclude'] ) ),
			'orderby'        => self::getIfSet( $settings['orderby'] ) ? $settings['orderby'] : 'menu_order',
			'order'          => self::getIfSet( $settings['order'] ) ? $settings['order'] : 'ASC',
			'meta_query'     => $meta_query,
			'post_type'      => $post_type,
			'paged'          => $paged,
			'tax_query'      => $tax_query
		);
		$posts     = new \WP_Query( $post_args );

		return $posts;
	}

	/**
	 *
	 */
	public static function get_current_page_namespace() {
		if(is_front_page()) {
			$id[] = "home";
		}
		else if(is_archive()) {
			if(is_post_type_archive()) {
				$id[] = get_post_type(get_the_ID());
			}
			$id[] = "archive";
		}
		elseif(is_home()) {
			$id[] ="blog";
		}
		else if(is_page()) {
			global $wp_query;
			$post = $wp_query->get_queried_object();
			if($post != null && property_exists($post, 'post_name'))
				$pagename = $post->post_name;
			else
				$pagename = "";
			$id[] = $pagename;
		}
		else if(is_single()) {
			$id[] = "single-".get_post_type(get_the_ID());
		}
		return $id;
	}


	/**
	 * @param $var
	 *
	 * @return null
	 */
	public static function getIfSet( & $var ) {
		if ( isset( $var ) ) {
			return $var;
		}

		return null;
	}

	/**
	 * @return string
	 */
	public static function get_current_pagename_id() {
		// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
		global $wp_query;
		$post = $wp_query->get_queried_object();

		if ( $post != null ) {
			$pagename = property_exists( $post, 'post_name' ) ? $post->post_name : $post->name;

			$postType = get_post_type_object( get_post_type( $post ) );

			if ( $postType ) {
				if ( is_single() ) {
					$pagename = strtolower( $postType->labels->singular_name . '-single' );
				}
			}
		} else {
			$pagename = "page-" . self::getToken( 4 );
		}

		if(!$pagename) {
			$pagename = get_query_var( 'pagename' );
		}
		return $pagename;
	}

	public static function svgUse($id, $attr ="", $attr_use="") {
		if(is_array($attr)) {
			$attr = Utils::attrToArray($attr);
		}

		if(is_array($attr_use)) {
			$attr_use = Utils::attrToArray($attr_use);
		}

		return '<svg '.$attr.'><use '.$attr_use.' xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'.$id.'" /></svg>';
	}

	/**
	 * @param $length
	 *
	 * @return bool|string
	 */
	public static function getToken( $length ) {
		return substr( str_shuffle( MD5( microtime() ) ), 0, 5 );;
	}

	/**
	 * @param $file
	 *
	 * @return bool|string
	 */
	public static function getTemplatePath( $file ) {
		if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
			return ( get_stylesheet_directory() . '/' . $file );
		} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
			return ( get_template_directory() . '/' . $file );
		} else {
			return false;
		}
	}

	public static function sym_error($message, $subtitle = '', $title = '') {
		$title = $title ?: __('Symbiotic Theme &rsaquo; Error', 'symbiotic');
		$footer = '';
		$message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
		wp_die($message, $title);
	}


}
