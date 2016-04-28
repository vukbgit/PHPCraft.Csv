<?php

namespace PHPCraft\Csv;

/**
 * Reads/creates CSV
 *
 * @author vuk <info@vuk.bg.it>
 */
interface CsvInterface
{
    /**
     * Sets delmiter
     *
     * @param string $delimiter
     **/
    public function setDelimiter($delimiter);
    
    /**
     * Sets column headers
     *
     * @param array $columnHeaders
     **/
    public function setColumnHeaders($columnHeaders);
    
    /**
     * creates and outputs CSV from array of objects
     *
     * @param string $name file name (without csv extension)
     * @param array $records
     * @return string csv content
     **/
    public function fromObjects($name, array $records);
    
    /**
     * builds HTTP headers for file open/download
     *
     * @param string $name file name (without csv extension)
     * @return array of associative arrays with keys 'name' and 'value'
     **/
    public function buildHttpHeaders($name);
}