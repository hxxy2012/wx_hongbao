<?php

/**
 * 导入excel的模型
 * Class M_zcq_import_excel
 */
class M_hb_import_excel extends M_common
{

    private $inputFileType = "excel5";//excel类型

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 设置excel类型
     * @param  string $inputFileType
     */
    public function setExcelType($inputFileType)
    {
        $this->inputFileType = $inputFileType;
    }

    /**
     * 获取excel操作对象
     * @param string $inputFileType excel类型
     * @param string $excelpath 文件路径
     * @return PHPExcel excel操作对象
     */
    public function getObjPHPExcel($inputFileType, $excelpath)
    {
        //读取数据
        require_once 'include/PHPExcel.php';
        require_once 'include/PHPExcel/Writer/Excel2007.php';

        //use Excel5 for 2003 format
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        // $excelpath   =  'myexcel.xlsx';//excel所在路径
        $objPHPExcel = $objReader->load($excelpath);
        return $objPHPExcel;
    }

    /**
     * 导出excel模板提供下载
     * @param string $table 导出的excel数据库表名字
     */
    public function exportExcelTpl($table)
    {
        $conArr = $this->getExcelTemplate($table);
        $conStr = implode('\\t', $conArr) . '\\t\\n';//组合成导出excel字符串
        $conStr = iconv('utf-8', 'gb2312', $conStr);
        $filename = date('YmdHis') . mt_rand(1, 10) . '.xls';
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.ms-execl");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $conStr;
    }

    /**
     * 获取excel导入模板,$table,
     * @param string $table 导入的表
     * @return array 返回一维模板数组
     */
    private function getExcelTemplate($table)
    {
        $result = null;
        switch ($table) {
            case 'zcq_datamanage'://走出去数据管理
                $result = array('园区或基地名称', '所在单位', '所属镇区', '经营面积');
                break;
        }
        return $result;
    }
}