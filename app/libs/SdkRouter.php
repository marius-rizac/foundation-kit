<?php

use Symfony\Component\Yaml\Yaml;

class SdkRouter extends Singleton
{
    protected $routes = array();

    public function register($name, $path, array $defaults=array())
    {
        $this->routes[$name]['path'] = $path;

        if ( count($defaults) > 0 ) {
            foreach ($defaults as $def_k => $def_v) {
                $this->routes[$name]['defaults']['{' . $def_k . '}'] = $def_v;
            }
        }
    }

    public function registerRoutes(array $routes=array())
    {
        foreach ($routes as $name => $item) {
            if ( $name == 'imports' ) {
                $skroute = SdkRouter::getInstance();
                foreach ($item as $route) {
                    $_new_routes = Yaml::parse(file_get_contents(CONFIG . '/' . $route['resource']));
                    $skroute->registerRoutes($_new_routes);
                }
                continue;
            }
            if ( ! isset($item['path']) ) {
                continue;
            }
            $defaults = ( isset($item['defaults']) ) ? $item['defaults'] : array();
            $this->register($name, $item['path'], $defaults);
        }
//        print_r($this->routes);
    }

    public function redirect($name, array $parameters=array())
    {
        if ( preg_match('/\//', $name) ) {
            header("Location: " . $name);
        } else {
            header("Location: " . $this->generate($name, $parameters));
        }
        die;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function generate($name, array $parameters=array())
    {
        if ( gettype($name) != 'string' ) {
            Monolog::err('$name must be of type string, ' . gettype($name) . ' given');
            throw new Exception('$name must be of type string, ' . gettype($name) . ' given');
        }
        if ($name === '' || empty($name)) {
            Monolog::info('Empty Route found');
            return '';
        }
        if ( isset($this->routes[$name]) ) {
            if ( isset($this->routes[$name]['defaults']) ) {
                $defaults = $this->routes[$name]['defaults'];
            } else {
                $defaults = null;
            }

            // get declared url path
            $url = $this->routes[$name]['path'];

            // add parameters passed to url
            if ( count($parameters) > 0 ) {
                $translate_params = array();
                foreach ($parameters as $par_k => &$par_v) {
                    $par_v = Utils::nice_url_title($par_v);
                    if ( preg_match('/\{' . $par_k . '\}/', $url) ) {
                        $translate_params['{'.$par_k.'}'] = $par_v;
                        unset($parameters[$par_k]);
                    }
                }
                $url = strtr($url, $translate_params);
            } else {
                if ( ! empty($defaults) ) {
                    $url = strtr($url, $defaults);
                }
            }

            // removing missing variables from url
            $url = preg_replace('/\{([a-z0-9\-_]+)\}/', '', $url);

            // add extra components for query
            if ( ! empty($parameters) ) {
                $url .= '?' . urldecode(http_build_query($parameters));
            }

            return $url;
        } else {
            //throw new Exception('Route ' . $name . ' not defined');
            Monolog::info('Route ' . $name . ' not defined');
        }
    }
}
