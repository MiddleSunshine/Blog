<?php

class FileManager
{
    protected $storeFileIndex;

    public $group;
    public $fileName;
    public function __construct()
    {
        $this->storeFileIndex=INCLUDE_ROOT."data".DIRECTORY_SEPARATOR;
        if (!file_exists($this->storeFileIndex)){
            mkdir($this->storeFileIndex);
            chmod($this->storeFileIndex, 0777);
        }
    }

    /**
     * 检测文件是否存在，支持子目录
     * @param $groupName array|string 字符串时，支持子目录 '1/2/3/4'，也支持数组 [1,2,3,4]
     * @param $fileName string 文件名
     * @return bool
     */
    public function checkFileExists($groupName,$fileName){
        $this->group=$groupName;
        $this->fileName=$fileName;
        $filePath=$this->getFilePath($groupName,$fileName);
        if (!$filePath){
            return false;
        }
        return $this->checkFileExistsByFullPath($filePath);
    }

    /**
     * 检测文件是否存在
     * @param $path string
     * @return bool
     */
    public function checkFileExistsByFullPath($path){
        if (empty($path)){
            return false;
        }
        return file_exists($path);
    }

    /**
     * 存储文件
     * @param $groupName array|string 字符串时，支持子目录 '1/2/3/4'，也支持数组 [1,2,3,4]
     * @param $fileName string 文件名
     * @param $storeData string 所存储的文件
     * @param $storeFileWhenFileExists bool 指定文件已经存在时，是否覆盖
     * @return bool|int
     */
    public function storeData($groupName,$fileName,$storeData,$storeFileWhenFileExists=true){
        $this->group=$groupName;
        $this->fileName=$fileName;
        $filePath=$this->getFilePath($groupName,$fileName);
        if (!$filePath){
            return false;
        }
        // 如果文件存在就不覆盖历史文件 && 文件存在
        if (!$storeFileWhenFileExists && $this->checkFileExists($groupName,$fileName)){
            return true;
        }
        // 文件名为空
        if (empty($fileName)){
            return false;
        }
        return file_put_contents($filePath,$storeData);
    }

    /**
     * @param $groupName string|array 字符串时，支持子目录 '1/2/3/4'，也支持数组 [1,2,3,4]
     * @param $fileName string
     * @param $defaultReturnData string 如果文件不存在时，返回默认值
     * @return false|mixed|string
     */
    public function getData($groupName,$fileName,$defaultReturnData=''){
        $this->group=$groupName;
        $this->fileName=$fileName;
        $filePath=$this->getFilePath($groupName,$fileName);
        if (!$filePath){
            return $defaultReturnData;
        }
        return $this->getDataByFullPath($filePath,$defaultReturnData);
    }

    /**
     * 获取文件
     * @param $path string
     * @param $defaultReturnData mixed
     * @return false|mixed|string
     */
    public function getDataByFullPath($path,$defaultReturnData=''){
        if (file_exists($path)){
            return file_get_contents($path);
        }
        return $defaultReturnData;
    }

    /**
     * 获取目录
     * @param $groupName string|array 字符串时，支持子目录 '1/2/3/4'，也支持数组 [1,2,3,4]
     * @param $fileName string
     * @return false|string
     */
    public function getFilePath($groupName,$fileName=''){
        $this->group=$groupName;
        $this->fileName=$fileName;
        if (empty($groupName)){
            return false;
        }
        // 字目录
        if (is_string($groupName)){
            $groupName=explode(DIRECTORY_SEPARATOR,$groupName);
        }
        // 去掉空字符串的目录
        $groupName=array_filter($groupName,function ($subDir){
            return !empty($subDir);
        });
        // 检测各个子目录是否存在，不存在就构建子目录
        $dir=$this->storeFileIndex;
        foreach ($groupName as $subDir){
            $dir=$dir.$subDir.DIRECTORY_SEPARATOR;
            if (!is_dir($dir)){
                mkdir($dir);
                chmod($dir, 0777);
            }
        }
        return $dir.$fileName;
    }
}