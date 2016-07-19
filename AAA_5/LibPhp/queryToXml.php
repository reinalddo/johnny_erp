<?
/**
 *Page        : queryToXml.php
 *Description : Process xmlhttprequest and format result appropriately for Rico LiveGrid
 *Date        : 4 June 2006
 *Authors     : Matt Brown (dowdybrown@yahoo.com)
 *Copyright (C) 2006 Matt Brown
**/
if (!isset ($_SESSION)) session_start();
require "dbclass.php";
require "General.inc.php";
require "GenUti.inc.php";
/**/
ob_start();
$oDB=new dbClass();
$oDB->debug=fGetParam("pDebug", false);
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + (-1*60)) . " GMT");
/**/
if ($oDB->debug != 2) {
    header("Content-type: text/xml; charset=ISO-8859-1");
} else  {
   print '<?xml version="1.0" encoding="iso-8859-1" ?'.">\n";
}
$id=fGetParam("id", false);
$offset=fGetParam("offset", 0);
$rowcnt=fGetParam("page_size", 1);
if ($rowcnt>200) $rowcnt=200;
set_error_handler("myErrorHandler");
/**/
$oDB->DisplayErrors=false;
$oDB->ErrMsgFmt="MULTILINE";
print "<ajax-response><response type='object' id='".$id."_updater'>";
/**/
if ($oDB->debug > 0) {
    print "\n<ajax-sqltext>" ;
    print_r($_SESSION[$id]);
    print "</ajax-sqltext>";
    print "\n<ajax-sql>";
    print_r($_SESSION);
    print "\n</ajax-sql>";
}
/**/
function fProcesar($pSql=false){
    global $oDB, $id, $sqltext, $offset, $rowcnt;
    $sqltext = ($pSql==false) ? $_SESSION[$id] : $pSql;
 // error_log("En func: " . $sqltext ." \n", 3,"/tmp/sql_log.err");
    if (!$id  && !isset($sqltext)) {
//    error_log("dentro: " . $sqltext ." \n", 3,"/tmp/sql_log.err");
      print "\n<rows update_ui='false' /><error>";
      print "\nNo ID provided!";
      print "\n</error>";
    } else if (!isset ($_SESSION[$id])) {
      print "\n<rows update_ui='false' /><error>";
      print "\nLa conexion con el servidor ha estado inactiva semasiado tiempo. Refresque la pagina y vuelva a intentarlo.";
      print "\n</error>";
      } else if ($oDB->MySqlLogon(DBNAME,DBUSER,DBPASS)) {  // NEED TO SET USERID AND PASSWORD HERE
        Query2xml($oDB,$sqltext,$id,$offset,$rowcnt, true);
        if ($oDB->LastErrorMsg) {
            print "\n<error>";
            print "\n" . htmlspecialchars($oDB->LastErrorMsg);
            print "\n</error>";
        }
      $oDB->dbClose();
      }
    print "\n</response></ajax-response>";
    $slXml = ob_get_contents();
    if ($oDB->debug > 0) {
        error_log("SALIDA XML: " .$slXml ."\n",3,"/tmp/sql2xml_out.err");
        echo "SALIDA: <pre>" . $slXml . "<pre>";
        ob_end_clean();
        error_log($slXml,3,"/tmp/sql2xml_out.xml");
    }else {
        ob_end_clean();
        print $slXml;
    }
}
// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
  print "\n<error>";
  print "\n".htmlspecialchars($errstr);
  print "\n</error>";
}
/**/
function Query2xml (&$oDB,$sqltext,$id,$offset,$numrows,$sendDebugMsgs)
{
//error_log("Qry: " . $sqltext ." \n", 3,"/tmp/sql_log.err");
  $oDB->ParseSqlSelect($sqltext,$arSelList,$FromClause,$WhereClause,$arGroupBy,$HavingClause,$arOrderBy);
  foreach($_GET as $variable => $value) {
    switch (substr($variable,0,1)) {
      case "s":
        $i=substr($variable,1);
        //$newsort=$arSelList[$i] . " " . $value;
        $newsort=($i+1) . " " . $value;
        $oDB->SetParseField($arOrderBy, $newsort);
        break;
      case "f":
        $i=substr($variable,1);
        $newfilter=$arSelList[$i] . " " . stripslashes($value);
        $oDB->AddCondition($WhereClause, $newfilter);
//       echo $WhereClause, $newfilter. "<br>";
        break;
    }
  }
  $sqltext=$oDB->UnparseSqlSelect($arSelList,$FromClause,$WhereClause,$arGroupBy,$HavingClause,$arOrderBy);
  $rsMain=$oDB->RunQuery($sqltext);
  if ($oDB->debug) error_log("SQL: " .$sqltext ."\n",3,"/tmp/sql2xml.err");
  if ($oDB->debug) error_log(DBNAME . "-" . DBSRVR ."\n",3,"/tmp/sql2xml.err");
  if (!$rsMain) return false;

  print "\n<rows update_ui='true' offset='".$offset."'>";
  $colcnt = mysql_num_fields($rsMain);
  $totcnt = mysql_num_rows($rsMain);
  //print "totcnt=".$totcnt." colcnt=".$colcnt." offset=".$offset." numrows=".$numrows;
  if ($offset < $totcnt)
  {
    $rowcnt=0;
    mysql_data_seek($rsMain,$offset);
    $alColSum=explode("," , fGetParam("pSum"), $rowcnt); // Columnas que requieren totales;
    $alSum=array();
    for ($i=0; $i < $colcnt; $i++)         $alSum[$i]=0;  // Encerar Totales

    while(($row = mysql_fetch_row($rsMain)) && $rowcnt < $numrows)
    {
      $rowcnt++;
      $slXmlTxt = "\n<tr>";
      for ($i=0; $i < $colcnt; $i++)
      {
        if (mysql_field_type($rsMain,$i)=="Date")
        {
          $slXmlTxt .= XmlStringCell(strftime("%m/%d/%Y",$row[$i]));
        }
          else
        {
          $slXmlTxt .= XmlStringCell($row[$i]);
          if (array_key_exists($i, $alColSum))  $alSum[$i] += $row[$i]; //    Acumular Si la columna requiere totales
        }
      }
      $slXmlTxt .= "</tr>";
      if ($oDB->debug) error_log($slXmlTxt . "\n",3,"/tmp/sql2xml_out.err");
      print $slXmlTxt;
    } 
  }
  else
  {
    $rowcnt=$offset;
  }

  print "\n"."</rows>";
  print "\n"."<rowcount>".$totcnt."</rowcount>";
  if ($sendDebugMsgs)
    print "\n<debug>" . htmlspecialchars($sqltext) . "</debug>";
  $oDB->rsClose($rsMain);
  return $rowcnt;
} 

function XmlStringCell($value)
{
  //$result=(isset($value)) ? str_replace("<","&lt;",str_replace("&","&amp;",$value)) : "";
  $result=(isset($value)) ? htmlspecialchars($value) : "";
  //$result=(isset($value)) ? $value : "";
  return "<td>".$result."</td>";
} 

?>
