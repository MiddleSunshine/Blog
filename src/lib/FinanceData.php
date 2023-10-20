<?php

class FinanceData
{
    public $FinanceFileDataIndex;
    public $fileManager;

    public function __construct()
    {
        $this->FinanceFileDataIndex = INCLUDE_ROOT . "data" . DIRECTORY_SEPARATOR . "Finance" . DIRECTORY_SEPARATOR;
        $this->fileManager = new FileManager();
        if (!$this->fileManager->checkFileExistsByFullPath($this->FinanceFileDataIndex)) {
            $this->fileManager->getFilePath($this->FinanceFileDataIndex);
        }
    }

    public function getData(FinanceSearch $search): array
    {
        $files = $this->getFiles($search);
        $returnData = [];
        foreach ($files as $file) {
            $this->getAllCSVData($file, $returnData, $search);
        }
        return $returnData;
    }

    public function newData(string $year, FinanceDataItem $item)
    {
        $this->appendCSVData(
            $this->getFileName($year),
            $item
        );
    }

    public function deleteData(string $year, string $ID)
    {
        $csvFile = $this->getFileName($year);
        $allData = [];
        $this->getAllCSVData($csvFile, $allData, false);
        $allData = array_filter($allData, function ($i) use ($ID) {
            return $i->ID != $ID;
        });
        $this->storeCSVData($csvFile, $allData);
    }

    public function updateOneData(string $year, FinanceDataItem $item): void
    {
        $csvFile = $this->getFileName($year);
        $allData = [];
        $this->getAllCSVData($csvFile, $allData, false);
        $allData[$item->ID] = $item;
        $this->storeCSVData($csvFile, $allData);
    }

    public function storeCSVData(string $file, array &$data): void
    {
        $templateFileName = $file . ".backup";
        $fileHandler = fopen($templateFileName, 'w');
        foreach ($data as $item) {
            /**
             * @var $item FinanceDataItem
             */
            fputcsv($fileHandler, $item->toArray());
        }
        fclose($fileHandler);
        rename($templateFileName, $file);
    }

    public function appendCSVData($file, FinanceDataItem $item)
    {
        file_put_contents($file, implode(',', $item->toArray()) . PHP_EOL, FILE_APPEND);
    }

    public function getAllCSVData(string $file, &$returnData, $search): void
    {
        if (!file_exists($file)) {
            return;
        }
        $fileHandler = fopen($file, 'r');
        while (!feof($fileHandler)) {
            $data = fgetcsv($fileHandler);
            $instance = new FinanceDataItem();
            $instance->initData($data);
            if ($search && $search->Category && $search->Category != $instance->Category) {
                continue;
            }
            if ($search && $search->Status && $search->Status != $instance->Status) {
                continue;
            }
            if ($search && $search->Remark && !str_contains($instance->Remark, $search->Remark)) {
                continue;
            }
            if ($search && $instance->dateTimeStamp >= $search->startDateTimeStamp && $instance->dateTimeStamp <= $search->endDateTimeStamp) {
                $returnData[$instance->ID] = $instance;
            }
        }
        fclose($fileHandler);
    }

    public function getFiles(FinanceSearch $search): array
    {
        list($startYear) = explode('-', $search->startDate);
        list($endYear) = explode("-", $search->endDate);
        $files = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $fileName = $this->getFileName($year);
            $files[$fileName] = $fileName;
        }
        return $files;
    }

    public function getFileName($year): string
    {
        return $this->fileManager->getFilePath($this->FinanceFileDataIndex . $year . DIRECTORY_SEPARATOR, $year . ".csv");
    }
}

class FinanceDataItem
{
    public $ID;
    public $Year;
    public $Month;
    public $Day;

    public $dateTimeStamp;
    public $Category;
    public $Remark;
    public $Status;
    public $Spend;

    public $dataTemplate = [
        'ID',
        'Year',
        'Month',
        'Day',
        'Status',
        'Spend',
        'Category',
        'Remark',
        'dateTimeStamp'
    ];

    public function initData($data): void
    {
        foreach ($this->dataTemplate as $index => $key) {
            $this->$key = $data[$index];
        }
        $this->dateTimeStamp = strtotime(sprintf("%s-%s-%s 12:00:00", $this->Year, $this->Month, $this->Day));
    }

    public function getData(): array
    {
        $returnData = [];
        foreach ($this->dataTemplate as $index => $key) {
            $returnData[$index] = $this->$key;
        }
        return $returnData;
    }

    public function toArray(): array
    {
        $returnData = [];
        foreach ($this->dataTemplate as $key) {
            $returnData[$key] = $this->$key;
        }
        return $returnData;
    }
}