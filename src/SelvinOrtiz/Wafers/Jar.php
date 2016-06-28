<?php
namespace SelvinOrtiz\Wafers;

use SelvinOrtiz\Dot\Dot;

/**
 * Class Jar
 *
 * @package SelvinOrtiz\CookieJar
 */
class Jar
{
    /**
     * @var static
     */
    protected static $instance;
    /**
     * @var Cookie[]
     */
    protected $cookies = [];

    /**
     * @return static
     */
    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();

            static::$instance->init();
        }

        return static::$instance;
    }

    /**
     * @param Cookie $cookie
     *
     * @return mixed
     */
    public function add(Cookie $cookie)
    {
        Dot::set($this->cookies, $cookie->name, $cookie);

        return setrawcookie(
            $cookie->name,
            (string)$cookie,
            $cookie->expires,
            $cookie->path,
            $cookie->domain,
            $cookie->secure,
            $cookie->httpOnly
        );
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function remove($name)
    {
        if (isset($this->cookies[$name]))
        {
            unset($this->cookies[$name]);
        }
        
        if (isset($_COOKIES[$name]))
        {
            unset($_COOKIES[$name]);
        }
        
        return setcookie($name, '', time() - 3600, '/');
    }

    /**
     * @param string     $name
     * @param null|mixed $default
     *
     * @return null|Cookie
     */
    public function get($name, $default = null)
    {
        return Dot::get($this->cookies, $name, $default);
    }

    /**
     * @return Cookie[]
     */
    public function all()
    {
        return $this->cookies;
    }

    protected function init()
    {
        if (!empty($_COOKIES)) {
            foreach ($_COOKIES as $name => $cookie) {
                $this->add(new Cookie($name, $cookie));
            }
        }
    }
}
