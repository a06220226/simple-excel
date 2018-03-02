<?php

/**++++++++++++++++++++++++++++++++++++++
 * @Created by PhpStorm
 * @User: ZHKJ lx
 * @Date: 2018/3/2 16:13
 * @desc: 导出excel
 **++++++++++++++++++++++++++++++++++++++
 */
class Excel {
    private static $file;

    //初始化设置header
    public function __construct() {
        set_time_limit(0);
        @header ( "Expires:-1" );
        @header ( 'Content-Type: application/vnd.ms-excel;charset=UTF-8' );
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        @header ( 'Content-Disposition: attachment;filename="'.date("Y-m-d").'.csv"' );
        @header ( 'Cache-Control: max-age=0');
        date_default_timezone_set('PRC');
    }

    public function exportCsv($title,$data) {
        if(!is_array($title) || !is_array($data))   exit('数据错误！');
        ob_end_clean();
        self::$file = fopen ( 'php://output', 'a' );
        self::setTitle($title);
        self::setData($data);
    }


    //设置标题
    private static function setTitle($title){

        foreach ($title as $i => &$v) {
            $v = iconv('utf-8', 'gbk', $v);
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv(self::$file, $title);
    }

    private static function setData($data){
        // 计数器
        $cnt = 0;
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        foreach ($data as $k => $v) {
            $cnt++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            foreach ($v as $key => &$value) {
                $value = iconv('utf-8', 'gbk', $value);
            }
            fputcsv(self::$file, $v);
        }

    }

}