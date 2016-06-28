<?php
namespace SelvinOrtiz\Wafers;

use SelvinOrtiz\Dot\Dot;

/**
 * Class Cookie
 *
 * @package SelvinOrtiz\CookieJar
 */
class Cookie
{
    public $name;
    public $value;
    public $expires;
    public $path;
    public $domain;
    public $secure;
    public $httpOnly;

    /**
     * @param string      $name     To identify the cookie
     * @param mixed       $value    To save as the value of this cookie
     * @param null|int    $expires  Date Interval spec of when cookie expires
     * @param null|string $path     Path where this cookie is shared in
     * @param null|string $domain   Domain where the cookie belongs
     * @param null|bool   $secure   Whether this cookie should only be sent if on HTTPS
     * @param null|bool   $httpOnly Whether this cookie is sent over HTTP only
     */
    public function __construct(
		$name,
		$value,
		$expires  = null,
		$path     = '/',
		$domain   = null,
		$secure   = false,
		$httpOnly = false
    ) {
		$this->name     = $name;
		$this->value    = $value;
		$this->expires  = (null === $expires) ? $this->getDefaultExpiration() : $this->getFutureTimestamp($expires);
		$this->path     = $path;
		$this->domain   = empty($domain) ? $this->getDefaultDomain() : $domain;
		$this->secure   = $secure;
		$this->httpOnly = $httpOnly;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->serialize();
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    public function serialize()
    {
        $json = json_encode($this->value);

        if (json_last_error()) {
            throw new \Exception(json_last_error_msg());
        }

        return $json;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    public function unserialize()
    {
        $raw = json_decode($this->value, true);

        if (json_last_error()) {
            throw new \Exception(json_last_error_msg());
        }

        return $raw;
    }

    /**
     * @return null|string
     */
    protected function getDefaultDomain()
    {
        return Dot::get($_SERVER, 'SERVER_NAME');
    }

    /**
     * @return int Future timestamp
     */
    protected function getDefaultExpiration()
    {
        return $this->getFutureTimestamp('P1Y');
    }
    
    protected function getFutureTimestamp($period = 'P1Y')
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        return $now->add(new \DateInterval($period))->format('U');
    }
}
