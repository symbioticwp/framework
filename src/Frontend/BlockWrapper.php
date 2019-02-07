<?php
namespace Symbiotic\Frontend;
use Symbiotic\Utils;

class BlockWrapper {

	public static function getInstance()
	{
		static $inst = null;
		if ($inst === null) {
			$inst = new BlockWrapper();
		}
		return $inst;
	}


	public function get_attrs($opts = array()) {
		return join(' ' , [$this->get_id(), $this->get_data_types(), $this->get_classnames()]);
	}

	public function get_id() {
		return apply_filters('symbiotic/frontend/pagesection/get_id', sprintf('id="%s"',
			'page-content-'.Utils::get_current_pagename_id()));
	}

	public function get_classnames() {
		$classnames = apply_filters('symbiotic/frontend/sb_wrapper/get_classnames', [
			'page-content',
			'page-content-ajax',
			$this->get_data_namespace() . '-page',
			'wrap'
		]);

		return sprintf('class="%s"',
			join(' ', $classnames));
	}

	public function get_data_types() {

		$data_attr = [
			'data-node-name' => $this->get_data_namespace(),
			'data-node-type' => $this->get_data_namespace(),
			'data-meta-title' => wp_title('&raquo;',false),
			'data-is-home' => is_front_page() ? 1 : 0
		];
		return apply_filters('symbiotic/frontend/pagesection/get_data_types', Utils::attrToArray($data_attr));
	}

	public function get_data_namespace() {
		$id = apply_filters('symbiotic/frontend/pagesection/get_data_namespace', Utils::get_current_page_namespace());
		return join('-', $id);
	}

	public function getDataMetaTitle() {
		if(get_the_title() !== '') {
			return get_the_title();
		}

		return ucfirst($this->get_data_namespace()) . ' Page';
	}

	private function yoastVariableToTitle($post_id) {
		$yoast_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
		$title = strstr($yoast_title, '%%', true);
		if (empty($title)) {
			$title = get_the_title($post_id);
		}
		$wpseo_titles = get_option('wpseo_titles');

		$sep_options = \WPSEO_Option_Titles::get_instance()->get_separator_options();
		if (isset($wpseo_titles['separator']) && isset($sep_options[$wpseo_titles['separator']])) {
			$sep = $sep_options[$wpseo_titles['separator']];
		} else {
			$sep = '-'; //setting default separator if Admin didn't set it from backed
		}

		$site_title = get_bloginfo('name');

		$meta_title = $title . ' ' . $sep . ' ' . $site_title;

		return $meta_title;
	}


}