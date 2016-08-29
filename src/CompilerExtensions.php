<?php

namespace Bladerunner;

/**
 * Handles extensions as well as custom extensions.
 */
class CompilerExtensions
{
    /**
     * Compiler extensions.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Get all compiler extensions.
     *
     * @return array
     */
    public static function getAllExtensions()
    {
        $result = new self();

        return $result->extensions;
    }

    /**
     * CompilerExtensions constructor.
     */
    public function __construct()
    {
        $this->addStandard();
        $this->addCustom();
    }

    /**
     * Adds WordPress specific extensions.
     */
    public function addStandard()
    {
        /*
            More to come in next version of Bladerunner
         */
    }

    /**
     * Adds custom extensions via WP filter.
     */
    public function addCustom()
    {
        $extensions = apply_filters('bladerunner/extend', []);
        if ($extensions && is_array($extensions)) {
            foreach ($extensions as $key => $filter_extension) {
                if (!is_array($filter_extension)) {
                    throw new \Exception("Bladerunner exeption requires an array, eg: \$extensions[] = ['pattern'=>'...', 'replace'=>'...'];");
                }

                $extension = new Extension($filter_extension);
                if ($extension->pattern && $extension->replace) {
                    $this->extensions[] = $extension;
                } else {
                    throw new \Exception("Bladerunner: Missing correct keys for custom extension! Eg, \$extensions[] = ['pattern'=>'...', 'replace'=>'...'];");
                }
            }
        }
    }

    /**
     * Magic get values.
     *
     * @param string $name
     */
    public function __get($name)
    {
        if ($name == 'extensions') {
            return $extensions;
        }

        return null;
    }
}
