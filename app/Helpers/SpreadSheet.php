<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadSheet
{
    protected $spreadSheet;

    public function __construct($file)
    {
        $this->spreadSheet = $this->loadFile($file);
    }

    /**
     * @param $file
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     *
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    public function read($file)
    {
        $reader = IOFactory::createReader(IOFactory::identify($file));
        $reader->setReadDataOnly(true);

        return $reader->load($file);
    }

    public function loadFile($file)
    {
        try {
            $spreadSheet = $this->read(base_path($file));
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return response()->json(['data' => $e->getMessage()]);
        }

        return $spreadSheet;
    }
    
    public function data()
    {
        try {
            $worksheetData = $this->spreadSheet->getActiveSheet()
                ->toArray(null, true, true, false);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json(['data' => $e->getMessage()]);
        }
        $headings = array_shift($worksheetData);
        array_walk(
            $worksheetData,
            function (&$row) use ($headings) {
                $row = array_combine($headings, $row);
            }
        );

        return collect($worksheetData);
    }
}
