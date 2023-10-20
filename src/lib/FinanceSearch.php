<?php

class FinanceSearch
{
    public $startDate;
    public $endDate;

    public $Category;
    public $Remark;

    public $Status;

    public $startDateTimeStamp;
    public $endDateTimeStamp;

    public $ID;

    public function __construct($initData)
    {
        foreach ($initData as $key => $value) {
            $this->$key = $value;
        }
        empty($this->endDate) && $this->endDate = date("Y-m-d");
        empty($this->startDate) && $this->startDate = date("Y-m-d", strtotime("-10 years"));
        $this->startDateTimeStamp = strtotime($this->startDate);
        $this->endDateTimeStamp = strtotime($this->endDate);
    }
}