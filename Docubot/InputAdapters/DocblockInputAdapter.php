<?php

namespace Docubot\InputAdapters;

/**
 * Input adapter for a custom docblock format documented in the readme
 * Class DocblockInputAdapter
 * @package Docubot\InputAdapters
 */
class DocblockInputAdapter extends InputAdapter
{
    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct($path, '/^.+\.php$/i');
    }
}