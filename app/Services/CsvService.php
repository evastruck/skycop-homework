<?php

namespace App\Services;

class CsvService
{
    /**
     * Path of the file that needs to be processed.
     *
     * @var string
     */
    private string $filePath;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Get data from a CSV file.
     *
     * @return array
     *   CSV data.
     */
    public function getData(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $data = [];

        $file = fopen($this->filePath, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $data[] = array_combine($header, $row);
        }

        fclose($file);

        return $data;
    }
}
