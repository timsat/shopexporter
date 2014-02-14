<?php

/***********************************
* 
* http://modx-shopkeeper.ru/
* Module Shopkeeper 0.9.6 RC1 for MODx
* Order Management
* 
***********************************/

defined('IN_MANAGER_MODE') or die();
define('SHR_PATH','../assets/modules/shopexporter');

function install($mod_table, $shopkeeper_table) {
    global $modx;
    $sql = array();
    //$sql[] = "CREATE TABLE IF NOT EXISTS `".$mod_table."` (`id` int(11) NOT NULL auto_increment,`date` datetime, PRIMARY KEY  (`id`))";
    //$sql[] = "CREATE TABLE IF NOT EXISTS `".$mod_table."` (`id` int(11) NOT NULL auto_increment,`shkExportId` int(11) not null,`shkId` int(11) not null, PRIMARY KEY  (`id`))";
    echo var_dump($sql);
    foreach ($sql as $line){
      $modx->db->query($line);
    }
}

//include "templates/debug.tpl.php";
//include "templates/install.tpl.php";

function trans($textcyr) {
    $cyr  = array('а','б','в','г','д','е','ё', 'ж','з','и','й','к','л','м','н','о','п','р','с','т','у', 
        'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','ы','э','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
        'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я','Ы','Э' );
    $lat = array( 'a','b','v','g','d','e','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
        'f' ,'h' ,'ts' ,'ch','sh' ,'sh' ,'a' ,'' ,'yu' ,'ya','y','e','A','B','V','G','D','E','Zh',
        'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
        'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sh' ,'A' ,'' ,'Yu' ,'Ya','Y','E' );
    if($textcyr) return str_replace($cyr, $lat, $textcyr);
}


function replaceCountry(&$res,$address,$variant,$country) {
    if (strpos($address,$variant) !== false) {
        $res['country'] = $country;
        $res['address'] = trans(str_replace($variant, $country, $address));
        return true;
    }
    return false;
}

function parseAddress($address) {
    $kRussia = array(
         'Россия'
        ,'Российская Федерация'
    );

    $kBelarus = array(
         'Беларусь'
        ,'Республика Беларусь'
        ,'Беларусия'
    );

    $kUkraine = array(
         'Украина'
    );

    $kKazahstan = array(
         'Казахстан'
    );
    $res = array();
    $a = str_replace(';',',',trans($address));
    foreach($kRussia as $variant){
        if (replaceCountry($res,$address,$variant,'Russia'))
            return $res;
    }
    foreach($kBelarus as $variant){
        if (replaceCountry($res,$address,$variant,'Belarus'))
            return $res;
    }
    foreach($kUkraine as $variant){
        if (replaceCountry($res,$address,$variant,'Ukraine'))
            return $res;
    }
    foreach($kKazahstan as $variant){
        if (replaceCountry($res,$address,$variant,'Kazahstan'))
            return $res;
    }
    return $res;
}

require_once "ShopExporter.php";

$exporter = new ShopExporter($modx);

$exporter->processRequest();

?>
