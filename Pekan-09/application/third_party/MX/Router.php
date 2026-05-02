<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MX_Router extends CI_Router
{
    public $module;
    private $located = 0;

    public function fetch_module()
    {
        return $this->module;
    }

    protected function _set_request($segments = array())
    {
        $segments = $this->_validate_request($segments);

        if (empty($segments))
        {
            $this->_set_default_controller();
            return;
        }

        if (isset($segments[1]) AND $segments[1] === 'index') unset($segments[1]);
        $this->set_class($segments[0]);
        isset($segments[1]) ? $this->set_method($segments[1]) : $segments[] = 'index';
        $this->uri->rsegments = $segments;
    }

    protected function _validate_request($segments)
    {
        if (count($segments) === 0) return $segments;

        /* locate module controller */
        if ($located = $this->locate($segments))  return $located;

        /* use CI default validation */
        return parent::_validate_request($segments);
    }

    public function locate($segments)
    {
        $this->located = 0;
        $ext = $this->config->item('controller_suffix') . '.php';

        /* use module route if exists */
        if (isset($segments[0]) AND $routes = Modules::parse_routes($segments[0], implode('/', $segments)))
        {
            $segments = $routes;
        }

        /* get module, controller */
        list($module, $directory, $controller) = array_pad($segments, 3, NULL);

        /* check module */
        foreach (Modules::$locations as $location => $offset)
        {
            /* module exists? */
            if (is_dir($source = $location . strtolower($module) . '/'))
            {
                $this->module = $module;
                $this->directory = $offset . strtolower($module) . '/';

                /* module sub-controller exists? */
                if ($directory)
                {
                    /* module sub-directory exists? */
                    if (is_dir($source . 'controllers/' . strtolower($directory) . '/'))
                    {
                        $source = $source . 'controllers/' . strtolower($directory) . '/';
                        $this->directory .= 'controllers/' . strtolower($directory) . '/';

                        /* module sub-directory controller exists? */
                        if ($controller)
                        {
                            if (is_file($source . ucfirst(strtolower($controller)) . $ext))
                            {
                                $this->located = 3;
                                return array_slice($segments, 2);
                            }
                        }

                        /* use module sub-directory default controller */
                        if (is_file($source . ucfirst(strtolower($directory)) . $ext))
                        {
                            $this->located = 2;
                            return array_slice($segments, 1);
                        }
                    }
                    elseif (is_file($source . 'controllers/' . ucfirst(strtolower($directory)) . $ext))
                    {
                        /* module controller exists? */
                        $this->located = 2;
                        return array_slice($segments, 1);
                    }
                }

                /* use module default controller */
                if (is_file($source . 'controllers/' . ucfirst(strtolower($module)) . $ext))
                {
                    $this->located = 1;
                    return $segments;
                }
            }
        }

        if ( ! empty($this->directory)) return;

        /* application sub-directory controller exists? */
        if ($directory)
        {
            if (is_file(APPPATH . 'controllers/' . strtolower($module) . '/' . ucfirst(strtolower($directory)) . $ext))
            {
                $this->directory = strtolower($module) . '/';
                return array_slice($segments, 1);
            }

            /* application sub-sub-directory controller exists? */
            if ($controller)
            {
                if (is_file(APPPATH . 'controllers/' . strtolower($module) . '/' . strtolower($directory) . '/' . ucfirst(strtolower($controller)) . $ext))
                {
                    $this->directory = strtolower($module) . '/' . strtolower($directory) . '/';
                    return array_slice($segments, 2);
                }
            }
        }

        /* application controller exists? */
        if (is_file(APPPATH . 'controllers/' . ucfirst(strtolower($module)) . $ext))
        {
            return $segments;
        }

        $this->located = -1;
    }
}