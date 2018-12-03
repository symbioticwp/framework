<?php
namespace Symbiotic\Frontend;
use Symbiotic;

/**
 * Class video
 * @package fewbricks\bricks
 */
class Hero
{
  const CUSTOMIZER_SECTION = 'mj_hero_section';
  const CUST_HERO_TYPE_SETTING = 'mj_hero_type';
  const CUST_HERO_VIDEO_URL = 'mj_hero_video_url';
  const CUST_HERO_HEIGHT_SETTING = 'mj_hero_height';

  public static function getInstance()
  {
      static $inst = null;
      if ($inst === null) {
          $inst = new Hero();
      }
      return $inst;
  }

  public function display_hero() {
    return $this->get_type() !== "none";
  }

  public function get_classnames() {
      $classNames = [];
      $option = get_theme_mod(self::CUST_HERO_HEIGHT_SETTING);

      if($option === null) {
        if(is_front_page())
            $className[] = "front big";
        elseif(is_home() || is_archive()) {
            $classNames[] = "small";
        }
        else {
            $classNames[] = "small";
        }
      } else {
        $classNames[] = $option;
      }

      $classNames[] = $this->get_type();

      return apply_filters('symbiotic/frontend/hero/get_classnames', join(' ', $classNames));
  }

  public function get_options() {
  	return [
  		'classnames' => $this->get_classnames(),
  		'data_types' => $this->get_dataTypes(),
  		'title_wrapper' => $this->get_title_wrapper(),
  		'mj_cb_background_image' => $this->get_field('mj_cb_background_image')
  	];
  }

  public function get_dataTypes() {
      $data_attr = [];
      $hero_type = $this->get_type();

      // // @TODO: needs a test, just fixed
      // if($this->get_field('mj_cb_scrollToID')) {
      //   $data_attr['scroll-to'] = $this->get_field('mj_cb_scrollToID')  ;
      // }

      if($hero_type !== "none") {
        $data_attr['data-node-type'] = $hero_type . "-block";
      }

      return apply_filters('symbiotic/frontend/hero/get_data_types', Utils::attrToArray($data_attr));
  }

  /**
   * Get main title and subtitle if available
   * @return string
   */
  public function get_title_wrapper() {
    $title = $this->get_title();
    $subtitle = $this->get_subtitle();
    $html  = "<h1 class='hero__title'>$title</h1>";
    if($subtitle) {
      $html .= "<span class='hero__subtitle'>$subtitle</span>";
    }
    return apply_filters('symbiotic/frontend/hero/get_title_wrapper',$html);
  }  

  public function get_title() {
      $title = '';

      if(is_front_page())
          $title = get_theme_mod('mj_header_image_title', 'Lorem ipsum');
      elseif(is_home()) {
          $postType = get_queried_object();
          if($postType)
            $title = esc_html($postType->post_title);
      }
      elseif(is_tax()) {
        $term = get_queried_object();
        if($term)
          $title = esc_html($term->name);
      }
      elseif(is_post_type_archive()) {
          $title = post_type_archive_title();
      }
      elseif(is_search()) {
        $title = sprintf(__('Suchergebnisse %s', 'mj'), get_search_query());
      }
      elseif(is_404()) {
        $title = __("Nicht gefunden", "mj");
      } else {
        $title = get_the_title();
      }


      return apply_filters('symbiotic/frontend/hero/get_title', $title);
  }

  /**
   * Get Hero Subtitle if available
   * @return [type] [description]
   */
  public function get_subtitle() {
    return '';
  }

  /**
   * Get The Hero Type (Parallax, Slider, Text, None)
   * @return string
   */
  public function get_type() {
    // get global option
    $option = get_theme_mod(self::CUST_HERO_TYPE_SETTING);

    if(is_front_page()) {
      $option = 'none';
    }

    // Check if WooCommerce (Shop Extension) is active
    if(class_exists('WooCommerce')) {
        $option = "text";
    }

    return apply_filters('symbiotic/frontend/hero/get_type',
    $option != null ? $option : 'parallax');
  }

  public function get_background_image() {
    $headerImg = '';

    // get global option
    if(!$headerImg) {
      if(get_header_image()) {
        $data = get_object_vars(get_theme_mod('header_image_data'));
       // Now check to see if there is an id
       $headerImg = is_array($data) && isset($data['attachment_id']) ? $data['attachment_id'] : false;
      }
    }
    return apply_filters('symbiotic/frontend/hero/get_header_image_src', $headerImg);
  }

  public function get_video_url() {
    $videoUrl = false;
    // get global option
    if(!$videoUrl && get_theme_mod(self::CUST_HERO_VIDEO_URL)) {
      $videoUrl = get_theme_mod(self::CUST_HERO_VIDEO_URL);
    } elseif(!$videoUrl) {
      $videoUrl = Assets::DEFAULT_VIDEO;
    }
    return apply_filters('symbiotic/frontend/hero/get_video_url', $videoUrl);
  }

  public function get_video_slider() {
    $videoSlider = false;
    return apply_filters('symbiotic/frontend/hero/get_video_slider', $videoSlider);
  }

  public function get_slider() {
    $slider = false;
    return apply_filters('symbiotic/frontend/hero/get_video_slider', $slider);
  }

}
