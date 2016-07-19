<?
if (!isset ($_SESSION)) session_start();
/*****************************************************************
 Page        : queryToXml_tibco.php
 Description : Process xmlhttprequest and format result appropriately for Tibco
 Date        : 4 June 2006
 Authors     : Matt Brown (dowdybrown@yahoo.com)
 Copyright (C) 2006 Matt Brown

******************************************************************/

require "dbclass.php";
require "General.inc.php";
require "GenUti.inc.php";

ob_start();
$oDB=new dbClass();
$oDB->debug=fGetParam("pDebug", false);
$oDB->fieldNames = true;
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + (-1*60)) . " GMT");

if ($oDB->debug != 2) {
    header("Content-type: text/xml; charset=iso-8859-1");
} else  {
   print '<?xml version="1.0" encoding="ISO-8859-1" ?'.">\n";
}

$id=fGetParam("id", false);
$offset=fGetParam("offset", 0);
$page_size=fGetParam("page_size", false);
if ($page_size>200) $page_size=200;
set_error_handler("myErrorHandler");

$oDB->DisplayErrors=false;
$oDB->ErrMsgFmt="MULTILINE";
print "<data jsxid='jsxroot'>\n";

if ($oDB->debug > 0) {
    print "\n<ajax-sqltext>" ;
    print_r($_SESSION[$id]);
    print "</ajax-sqltext>";
    print "\n<ajax-sql>";
    print_r($_SESSION);
    print "\n</ajax-sql>";
}
/*                          ----------------------- Fin de Instrucciones Globales
*/
function fProcesar($pSql=false){
    global $oDB, $id, $sqltext, $offset, $page_size;
    $sqltext = ($pSql==false) ? $_SESSION[$id] : $pSql;
 // error_log("En func: " . $sqltext ." \n", 3,"/tmp/sql_log.err");
    if (!$id  && !isset($sqltext)) {
//    error_log("dentro: " . $sqltext ." \n", 3,"/tmp/sql_log.err");
      print "\n<rows update_ui='false' /><error>";
      print "\nNo ID provided!";
      print "\n</error>";
    } /** else if (!isset ($_SESSION[$id])) {
      print "\n<rows update_ui='false' /><error>";
      print "\nLa conexion con el servidor ha estado inactiva semasiado tiempo. Refresque la pagina y vuelva a intentarlo.";
      print "\n</error>";
//      } else if ($oDB->MySqlLogon(DBNAME,DBUSER,DBPASS)) {  // NEED TO SET USERID AND PASSWORD HERE
      }*/ else if ($oDB->MySqlLogon('datos','root','xx')) {  // NEED TO SET USERID AND PASSWORD HERE
        Query2xml($oDB,$sqltext,$id,$offset,$page_size, true);
        if ($oDB->LastErrorMsg) {
            print "\n<error >";
            print "\n" . htmlspecialchars($oDB->LastErrorMsg);
            print "\n</error>";
        }
      $oDB->dbClose();
      }
    print "\n</data>";
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
  print "\n<error_>";
  print "\n".htmlspecialchars($errstr . " lin: " . $errline);
  print "\n</error_>";
}
/*
*   Ejecuta la consulta y devuelve una estructura XML con los registros resultantes
*   @param  $oDB        Objeto  Referencia al id de conexion a la BD
*   @param  $sqltext    String  Texto con la istruccion Sql
*   @param  $id         string  Identificador de la variable de sesion que contiene texto SQL
*   @param  $offset     Entero  Desplazamiento del registro inicial a devolver dentro del recordset
*   @param  $numrows    Entero  Número de registros que se desea devolver
*   @param  $sendDebug Msgs Bool Indica si debe enviarse mensajes de depuracion
*/
function Query2xml (&$oDB,$sqltext,$id,$offset,$numrows,$sendDebugMsgs) {
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
    $colcnt = mysql_num_fields($rsMain);
    $totcnt = mysql_num_rows($rsMain);
    if ($offset < $totcnt)      $rowcnt=0;
    else $rowcnt=$offset;
    mysql_data_seek($rsMain,$offset);
    $alColSum=explode("," , fGetParam("pSum"), $rowcnt); // Columnas que requieren totales;
    $alSum=array();
    for ($i=0; $i < $colcnt; $i++)         $alSum[$i]=0;  // Encerar Totales
    while(($row=mysql_fetch_array ($rsMain, MYSQL_ASSOC)) ) {
        if (!$numrows ||  $rowcnt < $numrows ){//           Si se pide un numero fijo de regs y el actual es menor a éste
            $rowcnt++;
            $slXmlTxt = "\n<record index='" . $rowcnt ."' ";
            foreach ($row as $slFieldName => $slValue) {                   // para cada campo
                $slXmlTxt .= $slFieldName ."='" ;
                if (mysql_field_type($rsMain,$slFieldName)=="Date")  {
                  $slXmlTxt .= XmlStringCell(strftime("%m/%d/%Y",$slValue));
                }
                else {
                  $slXmlTxt .= XmlStringCell($slValue);
                  if (array_key_exists($slFieldName, $alColSum))  $alSum[$i] += $slValue; //    Acumular Si la columna requiere totales
                }
                $slXmlTxt .= "' " ;
            }
        $slXmlTxt .= "/>";
        if ($oDB->debug) error_log($slXmlTxt . "\n",3,"/tmp/sql2xml_out.err");
        print $slXmlTxt;
        }
    } 
  if ($sendDebugMsgs)    print "\n<debug>" . htmlspecialchars($sqltext) . "</debug>";
  $oDB->rsClose($rsMain);
  return $rowcnt;
} 

function XmlStringCell($value)
{
  //$result=(isset($value)) ? str_replace("<","&lt;",str_replace("&","&amp;",$value)) : "";
  $result=(isset($value)) ? htmlspecialchars($value) : "";
  //$result=(isset($value)) ? $value : "";
  return "".$result."";
} 

?>
