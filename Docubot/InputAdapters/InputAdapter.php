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

    /**
     * @var resource|null
     */
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

    /**
     * Load a file from the disk
     * @param $path
     */
    protected function loadFile($path)
    {
        if ($this->currentFile != null) {
            fclose($this->currentFile);
        }
        $this->currentFile = fopen($path, 'r');
    }

    /**
     * Read current line from the file
     * @return string
     */
    protected function readFile()
    {
        return fgets($this->currentFile);
    }

    /**
     * Loop over every file and line, call the process line method
     * @throws \Exception
     */
    public function process()
    {
        while (($file = $this->nextFile()) != false) {
            $this->loadFile($file);
            while (($line = $this->readFile()) != false) {
                $this->processLine($line);
            }
        }
    }

    /**
     * This should be overriden by the child class otherwise an exception is thrown
     * This is where the main logic goes in the child class
     * @param string $line
     * @throws \Exception
     */
    public function processLine($line = '')
    {
        throw new \Exception($line . 'Input Adapter ' . get_class($this) . ' has not overriden the processLine method');
    }
}