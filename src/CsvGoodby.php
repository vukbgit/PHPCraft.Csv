<?php

namespace PHPCraft\Csv;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

/**
 * Reads/creates CSV
 *
 * @author vuk <info@vuk.bg.it>
 */
class CsvGoodby implements CsvInterface
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
        $config = new ExporterConfig();
        $config->setDelimiter($this->delimiter);
        $exporter = new Exporter($config);
        //$writer->output($name.'.csv');
        $exporter->export('php://temp', $records);
    }
}