<?php

namespace Docubot\InputAdapters;

/**
 * Base input adapter class that handles file searching and loading
 * Class InputAdapter
 * @package Docubot\InputAdapters
 */
abstract class InputAdapter
{
    /**
     * @var string[]
     */
    public $files;

    public $currentFile;

    /**
     * @var int
     */
    public $currentIndex;

    /**
     * @param string $path
     * @param string $pathRegex
     */
    public function __construct($path, $pathRegex = '')
    {
        $this->files = [];
        $this->currentIndex = -1;
        $this->currentFile = null;

        // Recursively scan the path
        $dir = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dir);
        //Apply a regex to the file names if required
        if ($pathRegex != '') {
            $iterator = new \RegexIterator($iterator, $pathRegex, \RecursiveRegexIterator::GET_MATCH);
        }

        foreach ($iterator as $name => $object) {
            $this->files[] = $name;
        }
    }

    /**
     * Return the path of the next file in the list
     * @return string
     * @throws \Exception
     */
    protected function nextFile()
    {

        if ($this->currentIndex < sizeof($this->files) - 1) {
            $this->currentIndex++;
            return $this->files[$this->currentIndex];
        }

        return false;
    }

    protected function loadFile($path)
    {
        if ($this->currentFile != null) {
            fclose($this->currentFile);
        }
        $this->currentFile = fopen($path, 'r');
    }

    protected function readFile(){
        return fgets($this->currentFile);
    }

    public function process(){
        while (($file = $this->nextFile()) != false) {
            $this->loadFile($file);
            while(($line = $this->readFile()) != false){
                echo($line);
            }
        }
    }
}