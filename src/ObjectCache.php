<?php

namespace Bladerunner;

use Illuminate\Filesystem\Filesystem;

class ObjectCache extends Filesystem
{
    /**
     * WordPress Object Cache instance.
     *
     * @var \WP_Object_Cache
     */
    private $cache;

    /**
     * ObjectCache constructor.
     */
    public function __construct()
    {
        $this->cache = new \WP_Object_Cache();
    }

    /**
     * Check if cache value exists by key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return !empty($this->cache->get($key, 'bladerunner'));
    }

    /**
     * Get cached value by key.
     *
     * @param string $key
     * @param bool   $lock
     *
     * @return mixed
     */
    public function get($key, $lock = false)
    {
        $value = $this->cache->get($key, 'bladerunner');

        return $value ? $value['content'] : null;
    }

    /**
     * Put value into object cache.
     *
     * @param string $key
     * @param string $value
     * @param bool   $lock
     *
     * @return mixed
     */
    public function put($key, $value, $lock = false)
    {
        return $this->cache->set($key, ['content' => $value, 'modified' => time()], 'bladerunner');
    }

    /**
     * Get last modified value by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function lastModified($key)
    {
        $value = $this->cache->get($key, 'bladerunner');

        return $value ? $value['modified'] : null;
    }
}
