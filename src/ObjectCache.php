<?php
namespace Bladerunner;

use Illuminate\Filesystem\Filesystem;

class ObjectCache extends Filesystem
{
  
    private $cache;

    public function __construct()
    {
        $this->cache = new \WP_Object_Cache();
    }

    public function exists($key)
    {
        return !empty($this->cache->get($key, 'bladerunner'));
    }

    public function get($key, $lock = false)
    {
        $value = $this->cache->get($key, 'bladerunner');
        return $value ? $value['content'] : null;
    }

    public function put($key, $value, $lock = false)
    {
        return $this->cache->set($key, ['content' => $value, 'modified' => time()], 'bladerunner');
    }

    public function lastModified($key)
    {
        $value = $this->cache->get($key, 'bladerunner');
        return $value ? $value['modified'] : null;
    }
}
