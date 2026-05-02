<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modules
{
    public static $registry = array();
    public static $locations;

    public static function initialize($locations)
    {
        self::$locations = $locations;
    }

    public static function find($module, $base, $directory, $file)
    {
        $locations = self::$locations;

        if (empty($locations)) return array(FALSE, $file);

        $module = strtolower($module);
        $directory = strtolower($directory);

        foreach ($locations as $location => $offset)
        {
            $module_dir = $location . $module . '/';

            if (is_dir($module_dir))
            {
                $path = $module_dir . $directory . '/';

                foreach (array(ucfirst($file), $file) as $class)
                {
                    if (is_file($path . $class . '.php'))
                    {
                        return array($path, $class);
                    }
                }
            }
        }

        return array(FALSE, $file);
    }

    public static function load($module = array())
    {
        if (is_array($module)) return $module;

        $params = NULL;

        if (($pos = strrpos($module, '/')) !== FALSE)
        {
            $params = trim(substr($module, $pos + 1));
            $module = trim(substr($module, 0, $pos));
        }

        return array($module => $params);
    }

    public static function parse_routes($module, $uri)
    {
        /* load the route file */
        if (! isset(self::$registry[$module]))
        {
            list($path) = self::find($module, '', 'config/', 'routes');

            if ($path === FALSE) return;

            include($path . 'routes' . '.php');

            self::$registry[$module] = isset($route) ? $route : NULL;
        }

        if (! isset(self::$registry[$module])) return;

        /* parse module routes */
        foreach (self::$registry[$module] as $key => $val)
        {
            $key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);

            if (preg_match('#^' . $key . '$#', $uri))
            {
                if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }
                return explode('/', $module . '/' . $val);
            }
        }
    }
}