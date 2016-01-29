<?php
namespace Bladerunner;

/**
 * A single extention
 */
class Extension
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var string or callable
     */
    protected $replace;

    /**
     * Setting up this extension from an array with specific parameters
     * @param array $extension Expected keys "pattern" and "replace" to create an extension
     */
    public function __construct(array $extension)
    {
        $this->setup($extension);
    }

    /**
     * Just a wrapper to going through the array given
     * @param  array  $extension
     * @return void
     */
    private function setup(array $extension)
    {
        if ($extension && is_array($extension)) {
            foreach ($extension as $key => $value) {
                if ($key === 'pattern') {
                    $this->pattern = $value;
                } elseif ($key === 'replace') {
                    $this->replace = $value;
                }
            }
        }
    }

    /**
     * Expose the keys pattern and replace
     * @param  string $name Can be "pattern" or "replace", otherwise it will return null
     * @return string|null
     */
    public function __get($name)
    {
        if ($name == 'pattern') {
            if (is_callable($this->pattern)) {
                return call_user_func($this->pattern);
            } else {
                return $this->pattern;
            }
        } elseif ($name=='replace') {
            if (is_callable($this->replace)) {
                return call_user_func($this->replace);
            } else {
                return $this->replace;
            }
        } else {
            return null;
        }
    }

    /**
     * Setters accepting the keys "pattern" or "replace"
     * @param string $name  Can be "pattern" or "replace"
     * @param void
     */
    public function __set($name, $value)
    {
        if ($name == 'pattern') {
            $this->pattern = $value;
        } elseif ($name=='replace') {
            $this->replace = $replace;
        }
    }
}
