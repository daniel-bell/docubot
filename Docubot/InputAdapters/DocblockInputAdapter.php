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
     * @var bool
     */
    private $inDocblock;

    private $attributes;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct($path, '/^.+\.php$/i');
        $this->attributes = [];
    }

    /**
     * @param string $line
     */
    public function processLine($line)
    {
        $line = trim($line);
        if (strlen($line) > 0) {
            if (strstr($line, '/**')) {
                $this->inDocblock = true;
            }

            if (strstr($line, '*/')) {
                $this->inDocblock = false;
                var_dump($this->attributes);
                $this->attributes = [];
            }

            if ($line[0] == '*') {
                $tokens = explode(' ', $line);
                $attributeName = 'none';

                $cleanTokens = [];
                foreach($tokens as $token){
                    $token = trim($token);
                    if(strlen($token) > 0){
                        $cleanTokens[] = $token;
                    }
                }

                $i = 0;
                $attributeIndex = 0;
                foreach ($cleanTokens as $token) {
                    if (strlen($token) > 0) {
                        if ($token[0] == '@') {
                            $attributeName = substr($token, 1, strlen($token));
                            $attributeIndex = $i;
                        }
                    }

                    switch ($attributeName) {
                        default:
                            break;
                        case 'param';
                            $this->attributes['params'][$cleanTokens[$attributeIndex + 2]] = array_slice($tokens, $attributeIndex + 2, sizeof($tokens));
                            break;
                    }
                    $i++;
                }
            }
        }
    }
}