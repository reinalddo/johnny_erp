<?php
// the line below is only needed if the include path is not set on php.ini
ini_set("include path","C:\AppServ\www\phpreports");
include_once "PHPReportMaker.php";
$oRpt = new PHPReportMaker();
$oRpt->setUser("root");
$oRpt->setPassword("");
$oRpt->setSQL("select CITY,NAME,TYPE,ITEM,VALUE from sales order by CITY,NAME,TYPE,ITEM"); 
$oRpt->setXML("sales.xml");
$oRpt->run();
?>
