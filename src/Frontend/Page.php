<?php
namespace Symbiotic\Frontend;

/**
 * Class video
 * @package fewbricks\bricks
 */
class Page
{
    public static function getInstance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Page();
        }
        return $inst;
    }

	/**
	 * Return Header instance
	 * @return header
	 */
	public static function header() {
		return Header::getInstance();
	}

    /**
     * Return Body instance
     * @return hero
     */
    public static function body() {
      return Body::getInstance();
    }

	/**
	 * Return Body instance
	 * @return hero
	 */
	public static function blockWrapper() {
		return BlockWrapper::getInstance();
	}

    /**
     * Return Hero instance
     * @return hero
     */
    public static function hero() {
      return Hero::getInstance();
    }
}
