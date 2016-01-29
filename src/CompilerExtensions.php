<?php
namespace Bladerunner;

/**
 * Handles extensions as well as custom extensions.
 */
class CompilerExtensions
{
    protected $extensions = [];

    public static function getAllExtensions()
    {
        $result = new CompilerExtensions();
        return $result->extensions;
    }

    public function __construct()
    {
        $this->addStandard();
        $this->addCustom();
    }

    /**
     * Adds WordPress specific extensions
     * @return void
     */
    public function addStandard()
    {
        /*
            More to come in next version of Bladerunner
         */
    }

    /**
     * Adds custom extensions via WP filter
     * @return void
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

    public function __get($name)
    {
        if ($name=='extensions') {
            return $extensions;
        }
        return null;
    }
}
