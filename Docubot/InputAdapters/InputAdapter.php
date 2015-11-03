<?php

namespace Docubot\InputAdapters;

/**
 * Class InputAdapter
 * @package Docubot\InputAdapters
 */
abstract class InputAdapter
{
    /**
     * @param string $path
     * @param string $pathRegex
     */
    public function __construct($path, $pathRegex = '')
    {
        // Recursively scan the path
        $dir = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dir);
        //Apply a regex to the file names if required
        if ($pathRegex != '') {
            $iterator = new \RegexIterator($iterator, $pathRegex, \RecursiveRegexIterator::GET_MATCH);
        }

        foreach ($iterator as $name => $object) {
            echo "$name\n";
        }
    }
}