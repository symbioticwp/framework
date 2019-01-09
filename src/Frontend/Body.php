<?php
namespace Symbiotic\Frontend;
use Symbiotic\Utils;

class Body {

  public static function getInstance()
{
	static $inst = null;
	if ($inst === null) {
		$inst = new Body();
	}
	return $inst;
}

  public function __construct() {
	  add_action('symbiotic/frontend/header', [&$this, "addGoogleTagManagerBody"]);
  }

  public function get_attrs($opts = array()) {
      return join(' ' , [$this->get_id(), $this->get_data_types(), $this->get_classnames()]);
  }

  public function addGoogleTagManagerBody() {
	  echo get_theme_mod( 'mj_google_tag_manager_body', '' );
  }

  /**
   * [get_id description]
   * @return [type] [description]
   *
   * ATTENTION: IF YOU CHANGE THIS FUNCTION DO THIS ALSO
   */
   public function get_id($raw = false) {
	$id = apply_filters('symbiotic/frontend/body/get_id', Utils::get_current_page_namespace());

	if(!$raw) {
		$id = 'id='. join('-', $id);
	}
	return $id;
  }

  public function get_data_types() {
      $data_attr = [];

      $header_style = get_theme_mod( 'header_scheme', 'light' );

      // default mj nav or alternative nav - effects the css which is used
      $navigation_type = get_theme_mod('navigation_type2', 'mj_default');
      //$data_attr['data-navigation-type'] = $navigation_type;

      return apply_filters('symbiotic/frontend/body/get_data_types', Utils::attrToArray($data_attr));
  }

  public function get_classnames() {
    return apply_filters('symbiotic/frontend/body/get_classnames',
      sprintf('class="%s" ', join(' ', get_body_class()))
    );
  }

}
