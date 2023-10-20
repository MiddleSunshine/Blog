<?php

class Finance
{
    public $searchInstance;

    public function __construct(FinanceSearch $financeSearch)
    {
        $this->searchInstance = $financeSearch;
    }

    public function getList():array
    {
        $financeData = new FinanceData();
        $data = $financeData->getData($this->searchInstance);
        $returnData = [
            'List' => [],
            'Summary' => [
                'DataAmount' => count($data),
                'SpendAmount' => 0
            ]
        ];
        foreach ($data as $item) {
            /**
             * @var FinanceDataItem $item
             */
            $returnData['List'][] = $item->toArray();
            $returnData['Summary']['SpendAmount'] += $item->Spend;
        }
        return $returnData;
    }

    public function updateList()
    {

    }

    public function getID()
    {
        return date("Y-m-d-H-i-s").".".rand(1,10000);
    }
}