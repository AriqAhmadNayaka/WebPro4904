<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_URI extends CI_URI {

    protected function _parse_request_uri()
    {
        if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $parsed_uri = parse_url('http://dummy'.$_SERVER['REQUEST_URI']);
        $query = isset($parsed_uri['query']) ? $parsed_uri['query'] : '';
        $uri = isset($parsed_uri['path']) ? rawurldecode($parsed_uri['path']) : '';

        if (isset($_SERVER['SCRIPT_NAME'][0])) {
            $script_name = rawurldecode($_SERVER['SCRIPT_NAME']);
            $script_dir = rawurldecode(dirname($_SERVER['SCRIPT_NAME']));

            if (strpos($uri, $script_name) === 0) {
                $uri = (string) substr($uri, strlen($script_name));
            } elseif (strpos($uri, $script_dir) === 0) {
                $uri = (string) substr($uri, strlen($script_dir));
            }
        }

        if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
            $query = explode('?', $query, 2);
            $uri = $query[0];
            $_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
        } else {
            $_SERVER['QUERY_STRING'] = $query;
        }

        parse_str($_SERVER['QUERY_STRING'], $_GET);

        if ($uri === '/' || $uri === '') {
            return '/';
        }

        return $this->_remove_relative_directory($uri);
    }
}
