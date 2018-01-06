<?php
/*
* The MIT License (MIT)
*
* Copyright (c) 2014 Christopher Tombleson <chris@cribznetwok.com>
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/
/**
* UserInput
* Helper class for sanitizing user input.
*
* @author Christopher Tombleson
*/
class UserInput {
    protected $post, $get, $cookie;

    /**
    * __construct
    *
    * Create a new instance of UserInput
    */
    public function __construct() {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->cookie = $_COOKIE;
    }

    /**
    * get
    * Get a value from $_GET and sanitize it
    *
    * @param string $key    Key to get from array
    * @param string $type   What type is the variable (string, email, int, float, encoded, url, email)
    * @param array  $option Options for filter_var
    * @return mixed will return false on failure
    */
    public function get($key, $type = 'string', $options = array()) {
        if (!isset($this->get[$key])) {
            return false;
        }

        return filter_var($this->get[$key], $this->get_filter($type), $options);
    }

    /**
    * post
    * Get a value from $_POST and sanitize it
    *
    * @param string $key    Key to get from array
    * @param string $type   What type is the variable (string, email, int, float, encoded, url, email)
    * @param array  $option Options for filter_var
    * @return mixed will return false on failure
    */
    public function post($key, $type='string', $options = array()) {
        if (!isset($this->post[$key])) {
            return false;
        }

        return filter_var($this->post[$key], $this->get_filter($type), $options);
    }

    /**
    * cookie
    * Get a value from $_COOKIE and sanitize it
    *
    * @param string $key    Key to get from array
    * @param string $type   What type is the variable (string, email, int, float, encoded, url, email)
    * @param array  $option Options for filter_var
    * @return mixed will return false on failure
    */
    public function cookie($key, $type='string', $options = array()) {
        if (!isset($this->cookie[$key])) {
            return false;
        }

        return filter_var($this->cookie[$key], $this->get_filter($type), $options);
    }

    private function get_filter($type) {
        switch (strtolower($type)) {
            case 'string':
                $filter = FILTER_SANITIZE_STRING;
                break;

            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;

            case 'float' || 'decimal':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                break;

            case 'encoded':
                $filter = FILTER_SANITIZE_ENCODED;
                break;

            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;

            case 'email':
                $filter = FILTER_SANITIZE_EMAIL;
                break;

            default:
                $filter = FILTER_SANITIZE_STRING;
        }

        return $filter;
    }
}
