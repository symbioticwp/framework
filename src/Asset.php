<?php

namespace Symbiotic;

use Symbiotic\Assets\ManifestInterface;

/**
 * Class Template
 * @package Roots\Sage
 * @author QWp6t
 */
class Asset
{
    public static $dist = '/dist';

    /** @var ManifestInterface Currently used manifest */
    protected $manifest;

    protected $asset;

    protected $dir;

    public function __construct($file, ManifestInterface $manifest = null)
    {
        $this->manifest = $manifest;
        $this->asset = $file;
    }

    public function __toString()
    {
        return $this->getUri();
    }

    /**
    *   Check File Extension and Choose whtever we load from parent or child theme
    *   Just for Production use (asset.json)
    */
    public function getUri()
    {
        $file = ($this->manifest ? $this->manifest->get($this->asset) : $this->asset);
	    return get_template_directory_uri() . self::$dist . "/$file";
    }
}
