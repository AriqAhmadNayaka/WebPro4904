<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MX_Loader extends CI_Loader
{
    protected $_module;
    public $_ci_plugins = array();
    public $_ci_cached_vars = array();

    public function initialize($controller = NULL)
    {
        /* set the module name */
        $this->_module = CI::$APP->router->fetch_module();

        /* initialize the loader variables */
        $this->_ci_initialize();

        /* auto-load module items */
        if ($controller AND $controller->autoload)
        {
            $autoload = $controller->autoload;
            isset($autoload['helper']) AND $this->helper($autoload['helper']);
            isset($autoload['language']) AND $this->language($autoload['language']);
            isset($autoload['libraries']) AND $this->library($autoload['libraries']);
            isset($autoload['drivers']) AND $this->driver($autoload['drivers']);
            isset($autoload['model']) AND $this->model($autoload['model']);
            isset($autoload['view']) AND $this->view($autoload['view']);
        }
    }

    public function _autoloader($autoload)
    {
        /* nothing to do */
    }

    public function module($module, $params = NULL)
    {
        if (is_array($module)) return $this->modules($module);

        $_alias = strtolower(basename($module));
        CI::$APP->$_alias = Modules::load(array(basename($module) => $params));
        return CI::$APP->$_alias;
    }

    public function modules($modules)
    {
        $_aliases = array();
        foreach (Modules::load($modules) as $_module => $params)
        {
            $_aliases[] = $this->module($_module, $params);
        }
        return $_aliases;
    }

    public function model($model, $alias = '', $db_conn = FALSE)
    {
        if (empty($model)) return $this;

        foreach (Modules::load($model) as $_alias => $params)
        {
            if (is_string($_alias))
            {
                $alias = $_alias;
                $model = $params;
                $params = NULL;
            }
            else
            {
                $model = $params;
            }

            if (empty($alias)) $alias = basename($model);

            $alias = strtolower($alias);

            if (isset(CI::$APP->$alias)) return $this;

            /* check module */
            list($path, $_alias) = Modules::find($model, $this->_module, 'models/');

            /* load model */
            if ($path === FALSE)
            {
                parent::model($model, $alias, $db_conn);
            }
            else
            {
                class_exists('CI_Model', FALSE) OR load_class('Model', 'core');

                if ($db_conn !== FALSE AND ! class_exists('CI_DB', FALSE))
                {
                    if ($db_conn === TRUE) $db_conn = '';
                    CI::$APP->load->database($db_conn, FALSE, TRUE);
                }

                if ( ! class_exists($_alias))
                {
                    require_once($path . $_alias . '.php');
                }

                CI::$APP->$alias = new $_alias();
                CI::$APP->load->_ci_models[] = $alias;
            }
        }

        return $this;
    }

    public function view($view, $vars = array(), $return = FALSE)
    {
        list($path, $_view) = Modules::find($view, $this->_module, 'views/');

        if ($path !== FALSE)
        {
            $this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
            $view = $_view;
        }

        return parent::view($view, $vars, $return);
    }

    public function library($library, $params = NULL, $object_name = NULL)
    {
        if (empty($library)) return $this;

        foreach (Modules::load($library) as $_alias => $params)
        {
            if (is_string($_alias))
            {
                $alias = $_alias;
                $library = $params;
                $params = NULL;
            }
            else
            {
                $library = $params;
            }

            if ($params === NULL AND $object_name !== NULL) $params = NULL;

            list($path, $_alias) = Modules::find(strtolower($library), $this->_module, 'libraries/');

            if ($path === FALSE)
            {
                parent::library($library, $params, $object_name);
            }
            else
            {
                if ( ! class_exists('CI_' . $_alias) AND ! class_exists($_alias))
                {
                    require_once($path . $_alias . '.php');
                }

                $this->_ci_init_library($library, 'CI_', $params, $object_name);
            }
        }

        return $this;
    }

    public function helper($helper = array())
    {
        foreach (Modules::load($helper) as $helper)
        {
            list($path, $_helper) = Modules::find($helper . '_helper', $this->_module, 'helpers/');

            if ($path !== FALSE)
            {
                include_once($path . $_helper . '.php');
            }
            else
            {
                parent::helper($helper);
            }
        }

        return $this;
    }

    public function language($language = array(), $lang = '')
    {
        foreach (Modules::load($language) as $language)
        {
            parent::language($language, $lang);
        }

        return $this;
    }

    public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        CI::$APP->config->load($file, $use_sections, $fail_gracefully, $this->_module);
        return $this;
    }
}