<?php

namespace DMP;

use Smarty;

class SmartyTemplateEngine
{
    /**
     * @var Smarty $smarty
     */
    private $smarty;

    private static $smartyCopy;

    private $path;

    private $expiration;

    private static $expirationCopy;

    private $isCachingEnabled = false;
    private static $staticIsCachingEnabled = false;

    public function __construct($path, $cacheable = false)
    {
        $this->path = $path;
        $this->smarty = new Smarty();

        self::$smartyCopy = $this;
        self::$expirationCopy = &$this->expiration;
        self::$staticIsCachingEnabled = &$this->isCachingEnabled;

        $this->isCachingEnabled = $cacheable;
    }

    public function render($file, $params = [])
    {
        $this->setCompileDirectory($file);
        $this->smarty->assign($params);

        $this->smarty->force_compile = true;

        $this->smarty->display($file . '.tpl');
    }

    public function fetch($file, $params = [])
    {
        $this->setCompileDirectory($file);
        $this->smarty->assign($params);

        return $this->smarty->fetch($file . '.tpl');
    }

    private function setCompileDirectory($path)
    {
        $elements = [];

        if (strpos($path, '/')) {
            $elements = explode('/', $path);
        } else if (strpos($path, '\\')) {
            $elements = explode('\\', $path);
        }

        $bundle = '';
        if ($elements[0]) {
            $bundle = $elements[0];
        } else {
            $bundle = $elements[1];
        }

        $this->smarty->setTemplateDir($this->path)
            ->setCompileDir($this->path . '/' . $bundle . '/compiled_templates')
            ->setCacheDir($this->path . '/' . $bundle . '/compiled_caches');

        if ($this->isCachingEnabled) {
            $this->smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
            $this->smarty->setCacheLifetime($this->expiration);
        }
    }

    public static function getSmarty()
    {
        return self::$smartyCopy;
    }

    public static function setExpiration($seconds = 60 * 2)
    {
        self::$staticIsCachingEnabled = true;
        self::$expirationCopy = $seconds;
    }
}