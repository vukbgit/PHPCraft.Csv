<?php

namespace PHPCraft\Csv;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

/**
 * Reads/creates CSV
 *
 * @author vuk <info@vuk.bg.it>
 */
class CsvGoodbyAdapter implements CsvInterface
{
    private $delimiter = ',';
    private $columnHeaders;
    
    /**
     * Sets delimiter
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
        try{
            foreach($records as &$record) {
                $record = (array) $record;
            }
            $config = new ExporterConfig();
            $config->setDelimiter($this->delimiter);
            if($this->columnHeaders) $config->setColumnHeaders($this->columnHeaders);
            $exporter = new Exporter($config);
            ob_start();
            $exporter->export('php://output', $records);
            return ob_get_clean();
        }catch(\Goodby\CSV\Export\Standard\Exception\StrictViolationException $exception){
            throw new \Exception('Wrong number of columns in CSV creation');
        }
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
    
    /**
     * reads a csv file and returns records into an array of arrays
     *
     * @param string $path to csv file
     * @param boolean $hasHeaders
     * @return array with csv content
     **/
    public function toArray($path, $hasHeaders = true)
    {
        try{
            $records = [];
            $config = new LexerConfig();
            $config->setDelimiter($this->delimiter);
            $lexer = new Lexer($config);
            $interpreter = new Interpreter();
            $rowIndex = 0;
            $columnHeaders = [];
            $interpreter->addObserver(function(array $row) use ($hasHeaders, &$rowIndex, &$columnHeaders, &$records) {
                //store headers
                if($hasHeaders && $rowIndex === 0) {
                    $columnHeaders = $row;
                } else {
                    $record = [];
                    //loop row values
                    foreach($row as $fieldIndex => $fieldValue) {
                        //store with header name
                        if($hasHeaders) {
                            $record[$columnHeaders[$fieldIndex]] = $fieldValue;
                        } else {
                        //store with numeric index
                            $record[] = $fieldValue;
                        }
                    }
                    //add record
                    $records[] = $record;
                }
                //increment row index
                $rowIndex++;
            });
            $lexer->parse($path, $interpreter);
            return $records;
        }catch(\Goodby\CSV\Export\Standard\Exception\StrictViolationException $exception){
            throw new \Exception('Wrong number of columns in CSV creation');
        }
    }
}