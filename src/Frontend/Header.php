<?php
namespace Symbiotic\Frontend;
use Symbiotic\Utils;

class Header {

    private $_inContainer;

    public static function getInstance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new Header();
        }
        return $inst;
    }

    public function __construct() {
	    add_action('mj_nav_button', [&$this, "get_mj_nav_button"]);
    }

    public function get_mj_nav_button() {
    	?>
	    <div class="menu-button menu-toggle">
		    <span class="burger-icon"></span>
	    </div>
		<?php
    }

    private function is_in_container() {
	    if($this->_inContainer === null) {
		    $in_container = get_theme_mod( 'header_in_container', 'relative' );

		    if ( get_theme_mod( 'header_position' ) === "fixed" ) {
			    $in_container = false;
		    }

		    $this->_inContainer = $in_container;
	    }
	    return $this->_inContainer;
    }

    public function header_container_start() {
	    if ($this->is_in_container()) {
		    $container_class = apply_filters('symbiotic/header/wrapper_container_class', 'container navbar-container');
		    echo '<div class="' . $container_class . '">';
	    }
    }

    public function header_container_end() {
        if($this->is_in_container()) {
            return '</div>';
        }
    }

    public function get_hor_position() {
        return apply_filters('symbiotic/frontend/nav/get_hor_position', get_theme_mod('header_hor_position', ''));
    }

    public function get_classnames() {

	    $classes = [];
        if($this->getcurrentheaderscheme() === "dark") {
            $classes[] = 'navbar-dark bg-dark';
        }

        switch(get_theme_mod('header_position', 'fixed')) {
	        case 'fixed':
		        $classes[] = 'fixed-top';
		        if(get_theme_mod('header_in_container')) {
		        	$classes[] = 'container';
		        }
	        	break;
	        case 'absolute':
		        $classes[] = 'absolute-top';
	        	break;
	        case 'sticky':
		        $classes[] = 'sticky-top';
		        break;
	        case 'relative':
		        $classes[] = '';
	        	break;
	        default:
	        	$classes[] = '';
	        	break;
        }

        return join(' ', $classes);
    }

	public function get_brand($type = 'light') {


		if ($this->getcurrentheaderscheme() !== $type)
			return;

		$bloginfo = get_bloginfo('name');

		if (get_theme_mod('logo_' . $type . '_use_svg_id', false) && get_theme_mod('logo_' . $type . '_svg_id')) {

			// get width
			$logo_max_width = get_theme_mod('logo_max_width');

			if ($logo_max_width) {
				$logo_max_width_style = ' style="width:' . $logo_max_width . '"';
			} else {
				$logo_max_width_style = '';
			}

			if ($type == 'light') {
				$lwidth = get_theme_mod('logo_light_svg_width');
				$lheight = get_theme_mod('logo_light_svg_height');
			} else {
				$lwidth = get_theme_mod('logo_dark_svg_width');
				$lheight = get_theme_mod('logo_dark_svg_height');
			}

			$viewbox = (!$lwidth || !$lheight) ? false : true;

			if ($viewbox) {
				$htmlattr = "viewbox='0 0 ${lwidth} ${lheight}' height='${lheight}' width='{$lwidth}'";
			} else {
				$htmlattr = "";
			}

			echo '<span class="logo">';
			echo Utils::svgUse(get_theme_mod('logo_' . $type . '_svg_id'), $htmlattr);
			//echo app\svg_use(get_theme_mod('logo_'.$type.'_svg_id'), $logo_max_width_style);
			echo '</span>';
		} else if (get_theme_mod('logo_' . $type)) {
			echo '<img src="' . get_theme_mod('logo_' . $type) . '" class="' . $type . '" alt="' . get_bloginfo('name') . '" />';
		} else {
			echo '<span class="logo-' . $type . '">' . $bloginfo . '</span>';
		}
	}


    public function getcurrentheaderscheme() {
        // get default header scheme set in header-base
        //
        $scheme = false;

        if (is_archive() || is_single()) {
            $scheme = get_theme_mod('blog_header_scheme', false);
        }

        if (!$scheme) {
            $scheme = get_theme_mod('header_scheme', false);
        }

        return $scheme;
    }
}