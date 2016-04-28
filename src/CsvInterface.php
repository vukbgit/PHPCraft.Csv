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
     * creates and outputs CSV from array of objects
     *
     * @param string $name file name (without csv extension)
     * @param array $records
     * @return string csv content
     **/
    public function fromObjects($name, array $records);    
}