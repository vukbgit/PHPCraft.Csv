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
    private $columnHeaders;
    
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
     * Sets column headers
     *
     * @param array $columnHeaders
     **/
    public function setColumnHeaders($columnHeaders)
    {
        $this->columnHeaders = $columnHeaders;
    }
    
    /**
     * creates and outputs CSV from array of objects
     *
     * @param string $name file name (without csv extension)
     * @param array $records
     * @return string csv content
     **/
    public function fromObjects($name, array $records)
    {
        $config = new ExporterConfig();
        $config->setDelimiter($this->delimiter);
        if($this->columnHeaders) $config->setColumnHeaders($this->columnHeaders);
        $exporter = new Exporter($config);
        ob_start();
        $exporter->export('php://output', $records);
        return ob_get_clean();
    }
    
    /**
     * builds HTTP headers for file open/download
     *
     * @param string $name file name (without csv extension)
     * @return associative array indexed by headers names and whose elements are headers values
     **/
    public function buildHttpHeaders($name)
    {
        return [
            'Content-Type' => 'text/csv',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="' . $name . '.csv"'
        ];
    }
}