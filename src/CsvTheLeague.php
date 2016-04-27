<?php

namespace PHPCraft\Csv;

use League\Csv\Writer;

/**
 * Reads/creates CSV
 *
 * @author vuk <info@vuk.bg.it>
 */
class CsvTheLeague implements CsvInterface
{
    private $delimiter = ',';
    
    /**
     * Sets delmiter
     *
     * @param string $delimiter
     **/
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }
    
    
    /**
     * creates and outputs CSV from array of objects
     *
     * @param string $name file name (without csv extension)
     * @param array $records
     **/
    public function fromObjects($name, array $records){
        if(!$this->writer) $this->writer = Writer::createFromFileObject(new SplTempFileObject());
        $this->writer->setDelimiter($this->delimiter);
        $this->writer->insertAll($records);
        $this->writer->output($name.'.csv');
    }
}