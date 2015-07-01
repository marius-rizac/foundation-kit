<?php

use Symfony\Component\HttpFoundation\Request;

class TwigData extends Helper
{
    const ERROR_404 = 'error/404.twig';

    const TEMPLATE_EXTENSION = '.twig';
    const SCRIPT_EXTENSION = '.php';

    /**
     * forced path used for testing purpose
     * @var null
     */
    protected $forcedPath = null;

    protected $template = '';
    protected $templateDefault = '';

    protected $output = array();

    protected $section;
    protected $view;

    protected $request;

    protected $dirs = array();

    protected static $instance = null;

    public static function getDefaultSection()
    {
        return 'home';
    }

    public static function getDefaultView()
    {
        return 'index';
    }

    /**
     * This class is not a singleton but it can be instantiated and used as a singleton
     *
     * @return null|static
     */
    static public function getInstance(Array $templateDirectories = array())
    {
        if ( null === self::$instance ) {
            self::$instance = new self($templateDirectories);
        }
        return self::$instance;
    }

    protected function __construct(Array $templateDirectories = array())
    {
        if ( null !== self::$instance ) {
            return self::$instance;
        }
        $this->request = Request::createFromGlobals();
        foreach ($templateDirectories as &$v) {
            $v = Utils::inc($v);
        }
        $this->dirs = array_reverse($templateDirectories);

        self::$instance = $this;

        $this->setSectionViewByPath();
    }

    public function getDirs()
    {
        return $this->dirs;
    }

    public function setController($controller)
    {
        $this->controller = str_replace('controller', '', strtolower($controller));
    }

    public function setTemplate($template_name)
    {
        $this->template = $template_name;
    }

    public function setTemplateDefault($template_name)
    {
        $this->templateDefault = $this->controller . '/' . $template_name;
    }

    public function getTemplate()
    {
        if ( empty($this->template) ) {
            $this->template = $this->templateDefault;
        }

        $templateExists = false;
        foreach ($this->dirs as $dir) {
            $fileToLoad = Utils::inc($dir . '/' . $this->template);
            if ( is_file($fileToLoad) ) {
                $templateExists = true;
                break;
            }
        }

        if ( $templateExists === true ) {
            return $this->template;
        } else {
            $message = sprintf(
                "Couldn't find template <b><em>%s</em></b> in %s",
                $this->template,
                '<ul><li>' . str_replace(dirname(dirname(LAYOUT)), '', @implode('</li><li>', $this->dirs)) . '</li></ul>'
            );
            $message .= sprintf(
                '<p>PHP script: <ul><li><b>%s</b></li></ul></p>',
                str_replace(dirname(dirname(MODULES)), '', $this->getScript())
            );
            $this->output['error'] = $message;
            return self::ERROR_404;
        }
    }

    public function set($key, $value='')
    {
        if ( is_array($key) && count($key) > 0 ) {
            foreach ($key as $k => $v) {
                $this->output[$k] = $v;
            }
        } else {
            if ( ! empty($key) ) {
                $this->output[$key] = $value;
            }
        }
    }

    public function getParam($id, $default=null)
    {
        $request = Request::createFromGlobals()->getPathInfo();
        $items = @explode('/', $request);

        return ( isset($items[$id]) && !empty($items[$id]) ) ? $items[$id] : $default;
    }

    public function getScript()
    {
        return Utils::inc(MODULES . '/' . $this->section . '/' . $this->view . self::SCRIPT_EXTENSION);
    }

    public function loadScript()
    {
        if ( is_file(MODULES . '/modules.php') ) {
            $modules = require_once MODULES . '/modules.php';
            if ( is_array($modules) ) {
                $this->output = array_merge($this->output, $modules);
            }
        }
        $file = $this->getScript();
        if ( is_file($file) ) {
            $output = require_once $file;
            $this->output = array_merge($this->output, $output);
        }
    }

    public function setSectionViewByPath()
    {
        $path = $this->request->getPathInfo();
        if ( strpos($path, '/') === 0 ) {
            $path = substr($path, 1);
        }
        $components = @explode('/', $path);

        // set section
        if ( isset($components[0]) && ! empty($components[0]) ) {
            $this->section = $components[0];
        } else {
            $this->section = self::getDefaultSection();
        }

        // set view
        if ( isset($components[1]) && ! empty($components[1]) ) {
            $this->view = $components[1];
        } else {
            $this->view = self::getDefaultView();
        }

        $this->setTemplate( Utils::inc($this->section . '/' . $this->view . self::TEMPLATE_EXTENSION) );
    }

    public function getOutput()
    {
        return array_merge(array(), $this->output);
    }

    /**
     * @return null
     */
    public function getForcedPath()
    {
        return $this->forcedPath;
    }

    /**
     * @param null $forcedPath
     */
    public function setForcedPath($forcedPath)
    {
        $this->forcedPath = $forcedPath;
    }
}
