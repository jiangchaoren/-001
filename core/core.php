<?php
    error_reporting(0);
	$default = true;
    define('SYSTEM_ROOT', dirname(__FILE__) . '/');
    define('ROOT', dirname(SYSTEM_ROOT) . '/');
    if (!file_exists(ROOT . 'install.lock')) {
        if (PHP_SAPI === 'cli') {
            exit("系统尚未安装，请访问 /install 完成安装。\n");
        }
        header('Content-Type: text/html; charset=utf-8');
        exit('<script>location.href="/install/";</script>');
    }
    include ROOT.'/core/function.php';
    include ROOT.'config.php';
    if (!defined('PIRATED_TRACKED')) {
               define('PIRATED_TRACKED', true);
    }
    include ROOT.'/core/db.class.php';
    $DB = new PdoHelper($dbconfig);
    $act = daddslashes($_GET['act'])??null;
    $mod = daddslashes($_GET['mod'])??'index';
    $thtime=date("Y-m-d").' 00:00:00';
    $the_url = $_SERVER['HTTP_HOST'];
    $order_id = date("YmdHis").mt_rand(100,999);;
    $tx_type = ['微信','支付宝','QQ'];

    $site_id = '0';
    $sitename = config('sitename');
    $title = config('title');
    $description = config('description');
    $keywords = config('keywords');
    $kfwx = config('kfwx');
    $kfqq = config('kfqq');
    $toudi_money = config('toudi_money');
    $chouqu_money = config('chouqu_money');
    $daili_money = config('daili_money');
    
    $siteInfo = $DB->getRow("SELECT * FROM `pre_site` WHERE `url`='{$the_url}'");
    if($siteInfo['url']==$the_url){
        $site_id = $siteInfo['id'];
        $sitename = $siteInfo['sitename'];
        $title = $siteInfo['title'];
        $description = $siteInfo['description'];
        $keywords = $siteInfo['keywords'];
        $kfwx = $siteInfo['kfwx'];
        $kfqq = $siteInfo['kfqq'];
        if(config('daili_zdy')){
            $toudi_money = $siteInfo['toudi'];
            $chouqu_money = $siteInfo['chouqu'];
            $daili_money = $siteInfo['daili'];
        }
    }
    
    if($_COOKIE['userToken']){
        $userName = daddslashes($_COOKIE['userName']);
        $userInfo = $DB->getRow("SELECT * FROM `pre_user` WHERE `user`='{$userName}'");
        $siteInfo = $DB->getRow("SELECT * FROM `pre_site` WHERE `user`='{$userInfo['user']}'");
        if($siteInfo){
            if($siteInfo['type']=='1'){
                $site_type = '实习月老';
            }elseif($siteInfo['type']=='2'){
                $site_type = '职业月老';
            }
        }
    }
    
    if(!$_COOKIE['userName'] && !$userInfo){
        $userName = md5(time().mt_rand(1,999999));
        setcookie("userName", $userName, time() + 604800, '/');
    }elseif($userInfo){
        setcookie("userName", $userInfo['user'], time() + 604800, '/');
    }
?>