<?php
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT']);
define('USER_CONFIG',ROOT_PATH.'/config/user_config.php');
define('SQL_FILE_PATH',ROOT_PATH.'/install/iWeibo.sql');
define('CHARSET', 'utf-8');
define('DBCHARSET', 'utf8');
define('INSTALLPATH',ROOT_PATH."/install");
define('INSTALLLOG',dirname(__FILE__).'/install.txt');
define('MB_ADMIN_DIR',ROOT_PATH.'/admin');
$iWeibo_funs_list = array('mysql_connect', 'fsockopen', 'gethostbyname', 'file_get_contents', 'xml_parser_create','mb_strlen','openssl_open');
$iWeibo_surrounding_list = array
(
        'os' => array('p'=>'操作系统 ','c' => 'PHP_OS', 'r' => '不限制', 'b' => 'unix'),
        'php' => array('p'=>'PHP版本','c' => 'PHP_VERSION', 'r' => '4.3', 'b' => '5.0'),
        'attachmentupload' => array('p'=>'附件上传','r' => '不限制', 'b' => '2M'),
        'gdversion' => array('p'=>'GD 库','r' => '1.0', 'b' => '2.0'),
        'diskspace' => array('p'=>'磁盘空间','r' => '10M', 'b' => '不限制')
);

$iWeibo_file_list = array
(
        'Tencent_admin' => array('type' => 'dir', 'path' => '../admin/data','minpower'=>2),
        'Tencent_config' => array('type' => 'dir', 'path' => '../config','minpower'=>2),
        'Tencent_install' => array('type' => 'dir', 'path' => '../install','minpower'=>2),
        'Tencent_style' => array('type' => 'dir', 'path' => '../style/images/admin/upload','minpower'=>2)
);
?>