<?php
/**
*
*   @rev	Jun/15/07  Fah	Cambio de Variables Globales y de sesion por $_SESSION y $_COOKIE
*   @rev	May/06/10  Fah	Cambiar session_register por asignacion directa a $_SESSION para soportar obsolesencia de session_register
*/
session_start();
header('Pragma: no-cache ');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

include(RelativePath . "/Classes.php");
include(RelativePath . "/db_mysql.php");
include_once("General.inc.php");

define("TemplatePath", "./");
define("ServerURL", "http://server/AAA/AAA_SEGURIDAD/");
define("SecureURL", "");

$Charset = "";

$ShortWeekdays = array("Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab");
$Weekdays = array("Domingo", "Lunes", "Martes", "Mi�rcoles", "Jueves", "Viernes", "S�bado");
$ShortMonths =  array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
$Months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

define("ccsInteger", 1);
define("ccsFloat", 2);
define("ccsText", 3);
define("ccsDate", 4);
define("ccsBoolean", 5);
define("ccsMemo", 6);

define("ccsGet", 1);
define("ccsPost", 2);

define("ccsTimestamp", 0);
define("ccsYear", 1);
define("ccsMonth", 2);
define("ccsDay", 3);
define("ccsHour", 4);
define("ccsMinute", 5);
define("ccsSecond", 6);
define("ccsMilliSecond", 7);
define("ccsAmPm", 8);
define("ccsShortMonth", 9);
define("ccsFullMonth", 10);
define("ccsWeek", 11);
define("ccsGMT", 12);
define("ccsAppropriateYear", 13);
//End Initialize Common Variables

//datos Connection Class @-FD3EBF71
class clsDBdatos extends DB_MySQL
{

    var $DateFormat;
    var $BooleanFormat;
    var $LastSQL;
    var $Errors;

    var $RecordsCount;
    var $RecordNumber;
    var $PageSize;
    var $AbsolutePage;

    var $SQL = "";
    var $Where = "";
    var $Order = "";

    var $Parameters;
    var $wp;

    function clsDBdatos()
    {
        $this->Initialize();
    }

    function Initialize()
    {
        $this->AbsolutePage = 0;
        $this->PageSize = 0;
       $this->DB = DBTYPE;
        $this->DBDatabase = DBNAME;
        $this->DBHost = DBSRVR;
        $this->DBUser = DBUSER;
        $this->DBPassword = DBPASS;
        $this->Persistent = true;
        $this->RecordsCount = 0;
        $this->RecordNumber = 0;
        $this->DateFormat = Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss");
        $this->BooleanFormat = Array("true", "false", "");
        $this->Uppercase = false;
        $this->LastSQL = "";
        $this->Errors = New clsErrors();
    }

    function MoveToPage($Page)
    {
        if($this->RecordNumber == 0 && $this->PageSize != 0 && $Page != 0)
            if( !$this->seek(($Page-1) * $this->PageSize)) {
                $this->Errors->addError("");
                $this->RecordNumber = $this->Row;
            } else {
                $this->RecordNumber = ($Page-1) * $this->PageSize;
            }
    }
    function PageCount()
    {
        return $this->PageSize ? ceil($this->RecordsCount / $this->PageSize) : 1;
    }

    function ToSQL($Value, $ValueType)
    {
        if(($ValueType == ccsDate && is_array($Value)) || strlen($Value) || ($ValueType == ccsBoolean && is_bool($Value)))
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsDate)
            {
                if (is_array($Value)) {
                    $Value = CCFormatDate($Value, $this->DateFormat);
                }
                return "'" . addslashes($Value) . "'";
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = CCFormatBoolean($Value, $this->BooleanFormat);
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else
                    $Value = "'" . addslashes($Value) . "'";
                return $Value;
            }
            else
            {
                return "'" . addslashes($Value) . "'";
            }
        }
        else
        {
            return "NULL";
        }
    }

    function SQLValue($Value, $ValueType)
    {
        if ($ValueType == ccsDate && is_array($Value)) {
            $Value = CCFormatDate($Value, $this->DateFormat);
        }
        if(!strlen($Value))
        {
            return "";
        }
        else
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = CCFormatBoolean($Value, $this->BooleanFormat);
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else
                    $Value = addslashes($Value);
                return $Value;
            }
            else
            {
                return addslashes($Value);
            }
        }
    }

    function query($SQL)
    {
        $this->LastSQL = $SQL;
        return parent::query($SQL);
    }

    function OptimizeSQL($SQL)
    {
        $PageSize = (int) $this->PageSize;
        if (!$PageSize) return $SQL;
        $Page = $this->AbsolutePage ? (int) $this->AbsolutePage : 1;
        $SQL .= " LIMIT " . (($Page - 1) * $PageSize) . "," .$PageSize;
        return $SQL;
    }

}
//End datos Connection Class

//seguridad Connection Class @-117D1604
class clsDBseguridad extends DB_MySQL
{

    var $DateFormat;
    var $BooleanFormat;
    var $LastSQL;
    var $Errors;

    var $RecordsCount;
    var $RecordNumber;
    var $PageSize;
    var $AbsolutePage;

    var $SQL = "";
    var $Where = "";
    var $Order = "";

    var $Parameters;
    var $wp;

    function clsDBseguridad()
    {
        $this->Initialize();
    }

    function Initialize()
    {
        $this->AbsolutePage = 0;
        $this->PageSize = 0;
        $this->DB = DBTYPE;
        $this->DBDatabase = "seguridad";
        $this->DBHost = DBSRVR;
        $this->DBUser = DBUSER;
        $this->DBPassword = DBPASS;
        $this->Persistent = true;
        $this->RecordsCount = 0;
        $this->RecordNumber = 0;
        $this->DateFormat = Array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss");
        $this->BooleanFormat = Array("true", "false", "");
        $this->Uppercase = false;
        $this->LastSQL = "";
        $this->Errors = New clsErrors();
    }

    function MoveToPage($Page)
    {
        if($this->RecordNumber == 0 && $this->PageSize != 0 && $Page != 0)
            if( !$this->seek(($Page-1) * $this->PageSize)) {
                $this->Errors->addError("");
                $this->RecordNumber = $this->Row;
            } else {
                $this->RecordNumber = ($Page-1) * $this->PageSize;
            }
    }
    function PageCount()
    {
        return $this->PageSize ? ceil($this->RecordsCount / $this->PageSize) : 1;
    }

    function ToSQL($Value, $ValueType)
    {
        if(($ValueType == ccsDate && is_array($Value)) || strlen($Value) || ($ValueType == ccsBoolean && is_bool($Value)))
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsDate)
            {
                if (is_array($Value)) {
                    $Value = CCFormatDate($Value, $this->DateFormat);
                }
                return "'" . addslashes($Value) . "'";
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = CCFormatBoolean($Value, $this->BooleanFormat);
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else
                    $Value = "'" . addslashes($Value) . "'";
                return $Value;
            }
            else
            {
                return "'" . addslashes($Value) . "'";
            }
        }
        else
        {
            return "NULL";
        }
    }

    function SQLValue($Value, $ValueType)
    {
        if ($ValueType == ccsDate && is_array($Value)) {
            $Value = CCFormatDate($Value, $this->DateFormat);
        }
        if(!strlen($Value))
        {
            return "";
        }
        else
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = CCFormatBoolean($Value, $this->BooleanFormat);
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else
                    $Value = addslashes($Value);
                return $Value;
            }
            else
            {
                return addslashes($Value);
            }
        }
    }

    function query($SQL)
    {
        $this->LastSQL = $SQL;
        return parent::query($SQL);
    }

    function OptimizeSQL($SQL)
    {
        $PageSize = (int) $this->PageSize;
        if (!$PageSize) return $SQL;
        $Page = $this->AbsolutePage ? (int) $this->AbsolutePage : 1;
        $SQL .= " LIMIT " . (($Page - 1) * $PageSize) . "," .$PageSize;
        return $SQL;
    }

}
//End seguridad Connection Class

//CCToHTML @0-93F44B0D
function CCToHTML($Value)
{
  return htmlspecialchars($Value);
}
//End CCToHTML

//CCToURL @0-88FAFE26
function CCToURL($Value)
{
  return urlencode($Value);
}
//End CCToURL

//CCGetEvent @0-C548DA85
function CCGetEvent($events, $event_name)
{
  $result = true;
  $function_name = (is_array($events) && isset($events[$event_name])) ? $events[$event_name] : "";
  if($function_name && function_exists($function_name))
    $result = call_user_func ($function_name);
  return $result;  
}
//End CCGetEvent

//CCGetValueHTML @0-254066EE
function CCGetValueHTML(&$db, $fieldname)
{
  return htmlspecialchars($db->f($fieldname));
}
//End CCGetValueHTML

//CCGetValue @0-36EF6396
function CCGetValue(&$db, $fieldname)
{
  return $db->f($fieldname);
}
//End CCGetValue

//CCGetSession @0-9BBC6D71
function CCGetSession($parameter_name)
{
    global $HTTP_SESSION_VARS;
    //return isset($HTTP_SESSION_VARS[$parameter_name]) ? $HTTP_SESSION_VARS[$parameter_name] : "";
    return isset($_SESSION[$parameter_name]) ? $_SESSION[$parameter_name] : "";
}
//End CCGetSession

//CCSetSession @0-0F088E96
function CCSetSession($param_name, $param_value)
{
    global $HTTP_SESSION_VARS;
    global ${$param_name};
    if(isset($param_name))   // fah May/07/10 session_is_registered($param_name))
	unset($_SESSION[$param_name]);
        // session_unregister($param_name);
    ${$param_name} = $param_value;
    //$_SESSION[$param_name];
    $HTTP_SESSION_VARS[$param_name] = $param_value;
    $_SESSION[$param_name] = $param_value;
}
//End CCSetSession

//CCGetCookie @0-705AF8CB
function CCGetCookie($parameter_name)
{
    global $HTTP_COOKIE_VARS;
    //return isset($HTTP_COOKIE_VARS[$parameter_name]) ? $HTTP_COOKIE_VARS[$parameter_name] : "";
    return isset($_COOKIE[$parameter_name]) ? $_COOKIE[$parameter_name] : "";
}
//End CCGetCookie

//CCSetCookie @0-1E0B074A
function CCSetCookie($parameter_name, $param_value)
{
  setcookie ($parameter_name, $param_value, time() + 3600 * 24 * 366);  
}
//End CCSetCookie

//CCStrip @0-34A0B0A2
function CCStrip($value)
{
  if(get_magic_quotes_gpc() != 0)
  {
    if(is_array($value))  
      for($j = 0; $j < sizeof($value); $j++)
        $value[$j] = stripslashes($value[$j]);
    else
      $value = stripslashes($value);
  }
  return $value;
}
//End CCStrip

//CCGetParam @0-7BF95E76
function CCGetParam($parameter_name, $default_value = "")
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $parameter_value = "";
    if(isset($HTTP_POST_VARS[$parameter_name]))
        $parameter_value = CCStrip($HTTP_POST_VARS[$parameter_name]);
    else if(isset($HTTP_GET_VARS[$parameter_name]))
        $parameter_value = CCStrip($HTTP_GET_VARS[$parameter_name]);
    else
        $parameter_value = $default_value;
    return $parameter_value;
}
//End CCGetParam

//CCGetParamStartsWith @0-3E113D15
function CCGetParamStartsWith($prefix)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $parameter_name = "";
    foreach($HTTP_POST_VARS as $key => $value) {
        if(preg_match ("/^" . $prefix . "_\d+$/i", $key)) {
            $parameter_name = $key;
            break;
        }
    }
    if($parameter_name === "") {
        foreach($HTTP_GET_VARS as $key => $value) {
            if(preg_match ("/^" . $prefix . "_\d+$/i", $key)) {
                $parameter_name = $key;
                break;
            }
        }
    }
    return $parameter_name;
}
//End CCGetParamStartsWith

//CCGetFromPost @0-EF0A789E
function CCGetFromPost($parameter_name, $default_value = "")
{
    global $HTTP_POST_VARS;
    return isset($HTTP_POST_VARS[$parameter_name]) ? CCStrip($HTTP_POST_VARS[$parameter_name]) : $default_value;
}
//End CCGetFromPost

//CCGetFromGet @0-F3558ED3
function CCGetFromGet($parameter_name, $default_value = "")
{
    global $HTTP_GET_VARS;
    return isset($HTTP_GET_VARS[$parameter_name]) ? CCStrip($HTTP_GET_VARS[$parameter_name]) : $default_value;
}
//End CCGetFromGet

//CCToSQL @0-422F5B92
function CCToSQL($Value, $ValueType)
{
  if(!strlen($Value))
  {
    return "NULL";
  }
  else
  {
    if($ValueType == ccsInteger || $ValueType == ccsFloat)
    {
      return doubleval(str_replace(",", ".", $Value));
    }
    else
    {
      return "'" . str_replace("'", "''", $Value) . "'";
    }
  }
}
//End CCToSQL

//CCDLookUp @0-AD41DC8E
function CCDLookUp($field_name, $table_name, $where_condition, &$db)
{
  $sql = "SELECT " . $field_name . ($table_name ? " FROM " . $table_name : "") . ($where_condition ? " WHERE " . $where_condition : "");
  return CCGetDBValue($sql, $db);
}
//End CCDLookUp

//CCGetDBValue @0-6DCF4DC4
function CCGetDBValue($sql, &$db)
{
  $db->query($sql);
  $dbvalue = $db->next_record() ? $db->f(0) : "";
  return $dbvalue;  
}
//End CCGetDBValue

//CCGetListValues @0-8E79EC2E
function CCGetListValues(&$db, $sql, $where = "", $order_by = "", $bound_column = "", $text_column = "", $dbformat = "", $datatype = "", $errorclass = "", $fieldname = "")
{
    $values = "";
    if(!strlen($bound_column))
        $bound_column = 0;
    if(!strlen($text_column))
        $text_column = 1;
    if(strlen($where))
        $sql .= " WHERE " . $where;
    if(strlen($order_by))
        $sql .= " ORDER BY " . $order_by;
    $db->query($sql);
    if ($db->next_record())
    {
        do
        {
            $bound_column_value = $db->f($bound_column);
            if($bound_column_value === false) {$bound_column_value = "";}
            list($bound_column_value, $errorclass) = CCParseValue($bound_column_value, $dbformat, $datatype, $errorclass, $fieldname);
            $values[] = array($bound_column_value, $db->f($text_column));
        } while ($db->next_record());
    }
    $result = ($errorclass == "") ? $values : array($values, $errorclass);
    return $result;
}

//End CCGetListValues

//CCParseValue @0-0CBCAA48
  function CCParseValue($ParsingValue, $Format, $DataType, $ErrorClass, $FieldName)
  {
    $varResult = "";
    if(CCCheckValue($ParsingValue, $DataType))
      $varResult = $ParsingValue;
    else if(strlen($ParsingValue))
    {
      switch ($DataType)
      {
        case ccsDate:
          $DateValidation = true;
          if (CCValidateDateMask($ParsingValue, $Format)) {
            $varResult = CCParseDate($ParsingValue, $Format);
            if(!CCValidateDate($varResult)) {
              $DateValidation = false;
              $varResult = "";
            }
          } else {
            $DateValidation = false;
          }
          if(!$DateValidation && $ErrorClass->Count() == 0) {
            if (is_array($Format)) {
              $FormatString = join("", $Format);
              $ErrorClass->addError("El campo " . $FieldName . " no es valido. Use el siguiente formato: " . $FormatString . ".");
            } else {
              $ErrorClass->addError("El campo " . $FieldName . " no es valido.");
            }
          }
          break;
        case ccsBoolean:
          if (CCValidateBoolean($ParsingValue, $Format)) {
            $varResult = CCParseBoolean($ParsingValue, $Format);
          } else if($ErrorClass->Count() == 0) {
            if (is_array($Format)) {
              $FormatString = CCGetBooleanFormat($Format);
              $ErrorClass->addError("El campo " . $FieldName . " no es valido. Use el siguiente formato: " . $FormatString . ".");
            } else {
              $ErrorClass->addError("El campo " . $FieldName . " no es valido.");
            }
          }
          break;
        case ccsInteger:
          if (CCValidateNumber($ParsingValue, $Format))
            $varResult = CCParseInteger($ParsingValue, $Format);
          else if($ErrorClass->Count() == 0)
            $ErrorClass->addError("El campo " . $FieldName . " no es valido.");
          break;
        case ccsFloat:
          if (CCValidateNumber($ParsingValue, $Format))
            $varResult = CCParseFloat($ParsingValue, $Format);
          else if($ErrorClass->Count() == 0)
            $ErrorClass->addError("El campo " . $FieldName . " no es valido.");
          break;
        case ccsText:
        case ccsMemo:
          $varResult = strval($ParsingValue);
          break;
      }
    }

    return array($varResult, $ErrorClass);
  }

//End CCParseValue

//CCFormatValue @0-14D74CBF
  function CCFormatValue($Value, $Format, $DataType)
  {
    switch($DataType)
    {
      case ccsDate:
        $Value = CCFormatDate($Value, $Format);
        break;
      case ccsBoolean:
        $Value = CCFormatBoolean($Value, $Format);
        break;
      case ccsInteger:
      case ccsFloat:
        $Value = CCFormatNumber($Value, $Format);
        break;
      case ccsText:
      case ccsMemo:
        $Value = strval($Value);
        break;
    }
    return $Value;
  }

//End CCFormatValue

//CCBuildSQL @0-AD00EEB4
function CCBuildSQL($sql, $where = "", $order_by = "")
{
    if(strlen($where))
        $sql .= " WHERE " . $where;
    if(strlen($order_by))
        $sql .= " ORDER BY " . $order_by;
    return $sql;
}

//End CCBuildSQL

//CCGetRequestParam @0-1C3CB87C
function CCGetRequestParam($ParameterName, $Method)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $ParameterValue = "";
    if($Method == ccsGet && isset($HTTP_GET_VARS[$ParameterName]))
        $ParameterValue = CCStrip($HTTP_GET_VARS[$ParameterName]);
    else if($Method == ccsPost && isset($HTTP_POST_VARS[$ParameterName]))
        $ParameterValue = CCStrip($HTTP_POST_VARS[$ParameterName]);
    return $ParameterValue;
}
//End CCGetRequestParam

//CCGetQueryString @0-F67D7840
function CCGetQueryString($CollectionName, $RemoveParameters)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $querystring = "";
    $postdata = "";
    if($CollectionName == "Form")
        $querystring = CCCollectionToString($HTTP_POST_VARS, $RemoveParameters);
    else if($CollectionName == "QueryString")
        $querystring = CCCollectionToString($HTTP_GET_VARS, $RemoveParameters);
    else if($CollectionName == "All")
    {
        $querystring = CCCollectionToString($HTTP_GET_VARS, $RemoveParameters);
        $postdata = CCCollectionToString($HTTP_POST_VARS, $RemoveParameters);
        if(strlen($postdata) > 0 && strlen($querystring) > 0)
            $querystring .= "&" . $postdata;
        else
            $querystring .= $postdata;
    }
    else
        die("1050: Common Functions. CCGetQueryString Function. " .
            "The CollectionName contains an illegal value.");
    return $querystring;
}
//End CCGetQueryString

//CCCollectionToString @0-883F2B49
function CCCollectionToString($ParametersCollection, $RemoveParameters)
{
  $Result = ""; 
  if(is_array($ParametersCollection))
  {
    reset($ParametersCollection);
    foreach($ParametersCollection as $ItemName => $ItemValues)
    {
      $Remove = false;
      if(is_array($RemoveParameters))
      {
        for($I = 0; $I < sizeof($RemoveParameters); $I++)
        {
          if($RemoveParameters[$I] == $ItemName)
          {
            $Remove = true;
            break;
          }
        }
      }
      if(!$Remove)
      {
        if(is_array($ItemValues))
          for($J = 0; $J < sizeof($ItemValues); $J++)
            $Result .= "&" . $ItemName . "[]=" . urlencode(CCStrip($ItemValues[$J]));
        else
           $Result .= "&" . $ItemName . "=" . urlencode(CCStrip($ItemValues));
      }
    }
  }

  if(strlen($Result) > 0)
    $Result = substr($Result, 1);
  return $Result;
}
//End CCCollectionToString

//CCMergeQueryStrings @0-6C3BA254
function CCMergeQueryStrings($LeftQueryString, $RightQueryString = "")
{
  $QueryString = $LeftQueryString; 
  if($QueryString === "")
    $QueryString = $RightQueryString;
  else if($RightQueryString !== "")
    $QueryString .= "&" . $RightQueryString;
  
  return $QueryString;
}
//End CCMergeQueryStrings

//CCAddParam @0-35F585B6
function CCAddParam($querystring, $ParameterName, $ParameterValue)
{
    $querystring = $querystring ? "&" . $querystring : "";
    $querystring = preg_replace ("/&".$ParameterName."(\[\])?=[^&]*/", "", $querystring);
    if(is_array($ParameterValue)) {
        for($i = 0; $i < sizeof($ParameterValue); $i++) {
            $querystring .= "&" . $ParameterName . "[]=" . urlencode($ParameterValue[$i]);
        }
    } else {
    $querystring .= "&" . $ParameterName . "=" . urlencode($ParameterValue);
    }
    $querystring = substr($querystring, 1);
    return $querystring;
}
//End CCAddParam

//CCRemoveParam @0-2AB4820E
function CCRemoveParam($querystring, $ParameterName)
{
    $querystring = "&" . $querystring;
    $Result = preg_replace ("/&".$ParameterName."(\[\])?=[^&]*/", "", $querystring);
    if (substr($Result, 0, 1) == "&")
        $Result = substr($Result, 1);
    return $Result;
}
//End CCRemoveParam

//CCGetOrder @0-27B4AC18
function CCGetOrder($DefaultSorting, $SorterName, $SorterDirection, $MapArray)
{
  if(is_array($MapArray) && isset($MapArray[$SorterName]))
    if(strtoupper($SorterDirection) == "DESC")
      $OrderValue = ($MapArray[$SorterName][1] != "") ? $MapArray[$SorterName][1] : $MapArray[$SorterName][0] . " DESC";
    else
      $OrderValue = $MapArray[$SorterName][0];
  else
    $OrderValue = $DefaultSorting;

  return $OrderValue;
}
//End CCGetOrder

//CCGetDateArray @0-F86CA386
function CCGetDateArray($timestamp = "")
{
  $DateArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
  if(!strlen($timestamp) && !is_int($timestamp)) {
    $timestamp = time();
  }

  $DateArray[ccsTimestamp] = $timestamp;
  $DateArray[ccsYear] = date("Y", $timestamp);
  $DateArray[ccsMonth] = date("n", $timestamp);
  $DateArray[ccsDay] = date("j", $timestamp);
  $DateArray[ccsHour] = date("G", $timestamp);
  $DateArray[ccsMinute] = date("i", $timestamp);
  $DateArray[ccsSecond] = date("s", $timestamp);

  return $DateArray;
}
//End CCGetDateArray

//CCFormatDate @0-79D717C2
function CCFormatDate($DateToFormat, $FormatMask)
{
  global $ShortWeekdays;
  global $Weekdays;
  global $ShortMonths;
  global $Months;

  if(!is_array($DateToFormat) && strlen($DateToFormat))
    $DateToFormat = CCGetDateArray($DateToFormat);

  if(is_array($FormatMask) && is_array($DateToFormat))
  {
    $masks = array(
      "LongTime" => "g:i:s A",
      "ShortTime" => "H:i", "d" => "j", "dd" => "d",
      "m" => "n", "mm" => "m", 
      "h" => "g", "hh" => "h", "H" => "G", "HH" => "H",
      "nn" => "i", "ss" => "s", "AM/PM" => "A", "am/pm" => "a"
    );
    $FormattedDate = "";
    for($i = 0; $i < sizeof($FormatMask); $i++)
    {
      if(isset($masks[$FormatMask[$i]]))
      {
        $FormattedDate .= date($masks[$FormatMask[$i]], $DateToFormat[ccsTimestamp]);
      }
      else
      {
        switch ($FormatMask[$i])
        {
          case "GeneralDate": 
            $FormattedDate .= date("n/j/", $DateToFormat[ccsTimestamp]);
            $FormattedDate .= substr($DateToFormat[ccsYear], 2);
            $FormattedDate .= date(", h:i:s A", $DateToFormat[ccsTimestamp]);
            break;
          case "LongDate": 
            $FormattedDate .= date("l, F j, ", $DateToFormat[ccsTimestamp]);
            $FormattedDate .= $DateToFormat[ccsYear];
            break;
          case "ShortDate": 
            $FormattedDate .= date("n/j/", $DateToFormat[ccsTimestamp]);
            $FormattedDate .= substr($DateToFormat[ccsYear], 2);
            break;
          case "yy": 
            $FormattedDate .= substr($DateToFormat[ccsYear], 2);
            break;
          case "yyyy": 
            $FormattedDate .= $DateToFormat[ccsYear];
            break;
          case "ddd": 
            $FormattedDate .= $ShortWeekdays[date("w", $DateToFormat[ccsTimestamp])];
            break;
          case "dddd": 
            $FormattedDate .= $Weekdays[date("w", $DateToFormat[ccsTimestamp])];
            break;
          case "w": 
            $FormattedDate .= (date("w", $DateToFormat[ccsTimestamp]) + 1);
            break;
          case "ww": 
            $FormattedDate .= ceil((6 + date("z", $DateToFormat[ccsTimestamp]) - date("w", $DateToFormat[ccsTimestamp])) / 7);
            break;
          case "mmm": 
            $FormattedDate .= $ShortMonths[date("n", $DateToFormat[ccsTimestamp]) - 1];
            break;
          case "mmmm":
            $FormattedDate .= $Months[date("n", $DateToFormat[ccsTimestamp]) - 1];
            break;
          case "q":
            $FormattedDate .= ceil(date("n", $DateToFormat[ccsTimestamp]) / 3);
            break;
          case "y":
            $FormattedDate .= (date("z", $DateToFormat[ccsTimestamp]) + 1);
            break;
          case "n": 
            $FormattedDate .= intval(date("i", $DateToFormat[ccsTimestamp]));
            break;
          case "s":
            $FormattedDate .= intval(date("s", $DateToFormat[ccsTimestamp]));
            break;
          case "S": 
            $FormattedDate .= $DateToFormat[ccsMilliSecond];
            break;
          case "A/P":
            $am = date("A", $DateToFormat[ccsTimestamp]);
            $FormattedDate .= $am[0];
            break;
          case "a/p":
            $am = date("a", $DateToFormat[ccsTimestamp]);
            $FormattedDate .= $am[0];
            break;
          case "GMT":
            $gmt = date("Z", $DateToFormat[ccsTimestamp]) / (60 * 60);
            if($gmt >= 0) $gmt = "+" . $gmt;
            $FormattedDate .= $gmt;
            break;
          default:
            $FormattedDate .= $FormatMask[$i];
            break;
        }
      }
    }
  }
  else
  {
    $FormattedDate = "";
  }
  return $FormattedDate;
}
//End CCFormatDate

//CCValidateDate @0-815AEC07
function CCValidateDate($ValidatingDate)
{
  $IsValid = true;
  if(is_array($ValidatingDate) && 
    $ValidatingDate[ccsMonth] != 0 && 
    $ValidatingDate[ccsDay] != 0 && 
    $ValidatingDate[ccsYear] != 0) 
  {
    $IsValid = checkdate($ValidatingDate[ccsMonth], $ValidatingDate[ccsDay], $ValidatingDate[ccsYear]);
  }

  return $IsValid;
}
//End CCValidateDate

//CCValidateDateMask @0-6A1F5673
function CCValidateDateMask($ValidatingDate, $FormatMask)
{
  $IsValid = true;
  if(is_array($FormatMask) && strlen($ValidatingDate))
  {
    $RegExp = CCGetDateRegExp($FormatMask);
    $IsValid = preg_match($RegExp[0], $ValidatingDate, $matches);
  }

  return $IsValid;
}
//End CCValidateDateMask

//CCParseDate @0-88C388A8
function CCParseDate($ParsingDate, $FormatMask)
{
  global $ShortMonths;
  global $Months;

  if(is_array($FormatMask) && strlen($ParsingDate))
  {
    $DateArray = array(0, "00", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $RegExp = CCGetDateRegExp($FormatMask);
    $IsValid = preg_match($RegExp[0], $ParsingDate, $matches);
    for($i = 1; $i < sizeof($matches); $i++)
      $DateArray[$RegExp[$i]] = $matches[$i];

    if($DateArray[ccsMonth] == 0 && ($DateArray[ccsFullMonth] != strval(0) || $DateArray[ccsShortMonth] != strval(0)))
    {
      if($DateArray[ccsFullMonth] != strval(0))
        $DateArray[ccsMonth] = CCGetIndex($Months, $DateArray[ccsFullMonth], true) + 1;
      else if($DateArray[ccsShortMonth] != strval(0))
        $DateArray[ccsMonth] = CCGetIndex($ShortMonths, $DateArray[ccsShortMonth], true) + 1;
    }

    if(intval($DateArray[ccsDay]) == 0) { $DateArray[ccsDay] = 1; }

    if($DateArray[ccsHour] < 12 && strtoupper($DateArray[ccsAmPm][0]) == "P")
      $DateArray[ccsHour] += 12;

    if($DateArray[ccsHour] == 12 && strtoupper($DateArray[ccsAmPm][0]) == "A")
      $DateArray[ccsHour] = 0;

    if(strlen($DateArray[ccsYear]) == 2)
      if($DateArray[ccsYear] < 70)
        $DateArray[ccsYear] = "20" . $DateArray[ccsYear];
      else
        $DateArray[ccsYear] = "19" . $DateArray[ccsYear];
      
    if($DateArray[ccsYear] < 1971 && $DateArray[ccsYear] > 0)
      $DateArray[ccsAppropriateYear] = $DateArray[ccsYear] + intval((2000 - $DateArray[ccsYear]) / 28) * 28;
    else if($DateArray[ccsYear] > 2030)
      $DateArray[ccsAppropriateYear] = $DateArray[ccsYear] - intval(($DateArray[ccsYear] - 2000) / 28) * 28;
    else      
      $DateArray[ccsAppropriateYear] = $DateArray[ccsYear];

    $DateArray[ccsTimestamp] = mktime ($DateArray[ccsHour], $DateArray[ccsMinute], $DateArray[ccsSecond], $DateArray[ccsMonth], $DateArray[ccsDay], $DateArray[ccsAppropriateYear]);
    if($DateArray[ccsTimestamp] < 0) $ParsingDate = "";
    else $ParsingDate = $DateArray;
    
  }

  return $ParsingDate;
}
//End CCParseDate

//CCGetDateRegExp @0-8DC6A9E4
function CCGetDateRegExp($FormatMask)
{
  global $ShortWeekdays;
  global $Weekdays;
  global $ShortMonths;
  global $Months;

  $RegExp = false;
  if(is_array($FormatMask))
  {
    $masks = array(
      "GeneralDate" => array("(\d{1,2})\/(\d{1,2})\/(\d{2}),?\s*(\d{2})?:?(\d{2})?:?(\d{2})?\s*(AM|PM)?", ccsMonth, ccsDay, ccsYear, ccsHour, ccsMinute, ccsSecond, ccsAmPm), 
      "LongDate" => array("(" . join("|", $Weekdays) . "), (" . join("|", $Months) . ") (\d{1,2}), (\d{4})", ccsWeek, ccsFullMonth, ccsDay, ccsYear),
      "ShortDate" => array("(\d{1,2})\/(\d{1,2})\/(\d{2})", ccsMonth, ccsDay, ccsYear), 
      "LongTime" => array("(\d{1,2}):(\d{2}):(\d{2})\s*(AM|PM)?", ccsHour, ccsMinute, ccsSecond, ccsAmPm),
      "ShortTime" => array("(\d{2}):(\d{2})", ccsHour, ccsMinute), 
      "d" => array("(\d{1,2})", ccsDay), 
      "dd" => array("(\d{2})", ccsDay), 
      "ddd" => array("(" . join("|", $ShortWeekdays) . ")", ccsWeek), 
      "dddd" => array("(" . join("|", $Weekdays) . ")", ccsWeek), 
      "w" => array("\d"), "ww" => array("\d{1,2}"),
      "m" => array("(\d{1,2})", ccsMonth), "mm" => array("(\d{2})", ccsMonth), 
      "mmm" => array("(" . join("|", $ShortMonths) . ")", ccsShortMonth), 
      "mmmm" => array("(" . join("|", $Months) . ")", ccsFullMonth),
      "y" => array("\d{1,3}"), "yy" => array("(\d{2})", ccsYear), 
      "yyyy" => array("(\d{4})", ccsYear), "q" => array("\d"),
      "h" => array("(\d{1,2})", ccsHour), "hh" => array("(\d{2})", ccsHour), 
      "H" => array("(\d{1,2})", ccsHour), "HH" => array("(\d{2})", ccsHour),
      "n" => array("(\d{1,2})", ccsMinute), "nn" => array("(\d{2})", ccsMinute), 
      "s" => array("(\d{1,2})", ccsSecond), "ss" => array("(\d{2})", ccsSecond), 
      "AM/PM" => array("(AM|PM)", ccsAmPm), "am/pm" => array("(am|pm)", ccsAmPm), 
      "A/P" => array("(A|P)", ccsAmPm), "a/p" => array("(a|p)", ccsAmPm),
      "GMT" => array("([\+\-]\d{2})", ccsGMT), "S" => array("(\d{1,6})", ccsMilliSecond)
    );
    $RegExp[0] = "";
    $RegExpIndex = 1;
    $is_date = false; $is_datetime = false;
    for($i = 0; $i < sizeof($FormatMask); $i++)
    {
      if(isset($masks[$FormatMask[$i]]))
      {
        $MaskArray = $masks[$FormatMask[$i]];
        if($i == 0 && ($MaskArray[1] == ccsYear || $MaskArray[1] == ccsMonth 
          || $MaskArray[1] == ccsFullMonth || $MaskArray[1] == ccsWeek || $MaskArray[1] == ccsDay))
          $is_date = true;
        else if($is_date && !$is_datetime && $MaskArray[1] == ccsHour)
          $is_datetime = true;
        $RegExp[0] .= $MaskArray[0];
        if($is_datetime) $RegExp[0] .= "?";
        for($j = 1; $j < sizeof($MaskArray); $j++)
          $RegExp[$RegExpIndex++] = $MaskArray[$j];
      }
      else
      {
        if($is_date && !$is_datetime && $i < sizeof($FormatMask) && $masks[$FormatMask[$i + 1]][1] == ccsHour)
          $is_datetime = true;
        $RegExp[0] .= CCAddEscape($FormatMask[$i]);
        if($is_datetime) $RegExp[0] .= "?";
      }
    }
    $RegExp[0] = str_replace(" ", "\s*", $RegExp[0]);
    $RegExp[0] = "/^" . $RegExp[0] . "$/i";
  }

  return $RegExp;
}
//End CCGetDateRegExp

//CCAddEscape @0-06D50C27
function CCAddEscape($FormatMask)
{
  $meta_characters = array("\\", "^", "\$", ".", "[", "|", "(", ")", "?", "*", "+", "{", "-", "]", "/");
  for($i = 0; $i < sizeof($meta_characters); $i++)
    $FormatMask = str_replace($meta_characters[$i], "\\" . $meta_characters[$i], $FormatMask);
  return $FormatMask;
}
//End CCAddEscape

//CCGetIndex @0-8DB8E12C
function CCGetIndex($ArrayValues, $Value, $IgnoreCase = true)
{
  $index = false;
  for($i = 0; $i < sizeof($ArrayValues); $i++)
  {
    if(($IgnoreCase && strtoupper($ArrayValues[$i]) == strtoupper($Value)) || ($ArrayValues[$i] == $Value))
    {
      $index = $i;
      break;
    }
  }
  return $index;
}
//End CCGetIndex

//CCFormatNumber @0-B39A1596
function CCFormatNumber($NumberToFormat, $FormatArray)
{
  $Result = "";
  if(is_array($FormatArray) && strlen($NumberToFormat))
  {
    $IsExtendedFormat = $FormatArray[0];
    $IsNegative = ($NumberToFormat < 0);
    $NumberToFormat = abs($NumberToFormat);
    $NumberToFormat *= $FormatArray[7];
  
    if($IsExtendedFormat) // Extended format
    {
      $DecimalSeparator = $FormatArray[2];
      $PeriodSeparator = $FormatArray[3];
      $ObligatoryBeforeDecimal = 0;
      $DigitsBeforeDecimal = 0;
      $BeforeDecimal = $FormatArray[5];
      $AfterDecimal = $FormatArray[6];
      if(is_array($BeforeDecimal)) {
        for($i = 0; $i < sizeof($BeforeDecimal); $i++) {
          if($BeforeDecimal[$i] == "0") {
            $ObligatoryBeforeDecimal++;
            $DigitsBeforeDecimal++;
          } else if($BeforeDecimal[$i] == "#") 
            $DigitsBeforeDecimal++;
        }
      }
      $ObligatoryAfterDecimal = 0;
      $DigitsAfterDecimal = 0;
      if(is_array($AfterDecimal)) {
        for($i = 0; $i < sizeof($AfterDecimal); $i++) {
          if($AfterDecimal[$i] == "0") {
            $ObligatoryAfterDecimal++;
            $DigitsAfterDecimal++;
          } else if($AfterDecimal[$i] == "#")
            $DigitsAfterDecimal++;
        }
      }
  
      $NumberToFormat = number_format($NumberToFormat, $DigitsAfterDecimal, ".", "");
      $NumberParts = explode(".", $NumberToFormat);

      $LeftPart = $NumberParts[0];
      if($LeftPart == "0") $LeftPart = "";
      $RightPart = isset($NumberParts[1]) ? $NumberParts[1] : "";
      $j = strlen($LeftPart);
    
      if(is_array($BeforeDecimal))
      {
        $RankNumber = 0;
        $i = sizeof($BeforeDecimal);
        while ($i > 0 || $j > 0)
        {
          if(($i > 0 && ($BeforeDecimal[$i - 1] == "#" || $BeforeDecimal[$i - 1] == "0")) || ($j > 0 && $i < 1)) {
            $RankNumber++;
            $CurrentSeparator = ($RankNumber % 3 == 1 && $RankNumber > 3 && $j > 0) ? $PeriodSeparator : "";
            if($ObligatoryBeforeDecimal > 0 && $j < 1)
              $Result = "0" . $CurrentSeparator . $Result;
            else if($j > 0)
              $Result = $LeftPart[$j - 1] . $CurrentSeparator . $Result;
            $j--;
            $ObligatoryBeforeDecimal--;
            $DigitsBeforeDecimal--;
            if($DigitsBeforeDecimal == 0 && $j > 0)
              for(;$j > 0; $j--)
              {
                $RankNumber++;
                $CurrentSeparator = ($RankNumber % 3 == 1 && $RankNumber > 3 && $j > 0) ? $PeriodSeparator : "";
                $Result = $LeftPart[$j - 1] . $CurrentSeparator . $Result;
              }
          }
          else if ($i > 0) {
            $BeforeDecimal[$i - 1] = str_replace("##", "#", $BeforeDecimal[$i - 1]);
            $BeforeDecimal[$i - 1] = str_replace("00", "0", $BeforeDecimal[$i - 1]);
            $Result = $BeforeDecimal[$i - 1] . $Result;
          }
          $i--;
        }
      }

      // Left part after decimal
      $RightResult = "";
      $IsRightNumber = false;
      if(is_array($AfterDecimal))
      {
        $IsZero = true;
        for($i = sizeof($AfterDecimal); $i > 0; $i--) {
          if($AfterDecimal[$i - 1] == "#" || $AfterDecimal[$i - 1] == "0") {
            if($DigitsAfterDecimal > $ObligatoryAfterDecimal) {
              if($RightPart[$DigitsAfterDecimal - 1] != "0") 
                $IsZero = false;
              if(!$IsZero)
              {
                $RightResult = $RightPart[$DigitsAfterDecimal - 1] . $RightResult;
                $IsRightNumber = true;
              }
            } else {
              $RightResult = $RightPart[$DigitsAfterDecimal - 1] . $RightResult;
              $IsRightNumber = true;
            }
            $DigitsAfterDecimal--;
          } else {
            $AfterDecimal[$i - 1] = str_replace("##", "#", $AfterDecimal[$i - 1]);
            $AfterDecimal[$i - 1] = str_replace("00", "0", $AfterDecimal[$i - 1]);
            $RightResult = $AfterDecimal[$i - 1] . $RightResult;
          }
        }
      }
    
      if($IsRightNumber)
        $Result .= $DecimalSeparator ;

      $Result .= $RightResult;

      if(!$FormatArray[4] && $IsNegative && $Result)
        $Result = "-" . $Result;
    }
    else // Simple format
    {
      if(!$FormatArray[4] && $IsNegative)
        $Result = "-" . $FormatArray[5] . number_format($NumberToFormat, $FormatArray[1], $FormatArray[2], $FormatArray[3]) . $FormatArray[6];
      else
        $Result = $FormatArray[5] . number_format($NumberToFormat, $FormatArray[1], $FormatArray[2], $FormatArray[3]) . $FormatArray[6];
    }

    if(!$FormatArray[8])
      $Result = htmlspecialchars($Result);

    if(strlen($FormatArray[9]))
      $Result = "<FONT COLOR=\"" . $FormatArray[9] . "\">" . $Result . "</FONT>";
  }
  else
  {
    $Result = $NumberToFormat;
  }

  return $Result;
}
//End CCFormatNumber

//CCValidateNumber @0-D53857C4
function CCValidateNumber($NumberValue, $FormatArray)
{
  $is_valid = true;
  if(strlen($NumberValue))
  {
    $NumberValue = CCCleanNumber($NumberValue, $FormatArray);
    $is_valid = is_numeric($NumberValue);
  }
  return $is_valid;
}

//End CCValidateNumber

//CCParseNumber @0-51D95F29
function CCParseNumber($NumberValue, $FormatArray, $DataType)
{
  $NumberValue = CCCleanNumber($NumberValue, $FormatArray);
  if(is_array($FormatArray) && strlen($NumberValue))
  {

    if($FormatArray[4]) // Is use parenthesis
      $NumberValue = - abs(doubleval($NumberValue));

    $NumberValue /= $FormatArray[7];
  }

  if(strlen($NumberValue))
  {
    if($DataType == ccsFloat)
      $NumberValue = doubleval($NumberValue);
    else
      $NumberValue = round($NumberValue, 0);
  }

  return $NumberValue;
}
//End CCParseNumber

//CCCleanNumber @0-2A278526
function CCCleanNumber($NumberValue, $FormatArray)
{
  if(is_array($FormatArray))
  {
    $IsExtendedFormat = $FormatArray[0];

    if($IsExtendedFormat) // Extended format
    {
      $BeforeDecimal = $FormatArray[5];
      $AfterDecimal = $FormatArray[6];
    
      if(is_array($BeforeDecimal))
      {
        for($i = sizeof($BeforeDecimal); $i > 0; $i--) {
          if($BeforeDecimal[$i - 1] != "#" && $BeforeDecimal[$i - 1] != "0") 
          {
            $search = $BeforeDecimal[$i - 1];
            $search = ($search == "##" || $search == "00") ? $search[0] : $search;
            $NumberValue = str_replace($search, "", $NumberValue);
          }
        }
      }

      if(is_array($AfterDecimal))
      {
        for($i = sizeof($AfterDecimal); $i > 0; $i--) {
          if($AfterDecimal[$i - 1] != "#" && $AfterDecimal[$i - 1] != "0") 
          {
            $search = $AfterDecimal[$i - 1];
            $search = ($search == "##" || $search == "00") ? $search[0] : $search;
            $NumberValue = str_replace($search, "", $NumberValue);
          }
        }
      }
    }
    else // Simple format
    {
      if(strlen($FormatArray[5]))
        $NumberValue = str_replace($FormatArray[5], "", $NumberValue);
      if(strlen($FormatArray[6]))
        $NumberValue = str_replace($FormatArray[6], "", $NumberValue);
    }

    if(strlen($FormatArray[3]))
      $NumberValue = str_replace($FormatArray[3], "", $NumberValue); // Period separator
    if(strlen($FormatArray[2]))
      $NumberValue = str_replace($FormatArray[2], ".", $NumberValue); // Decimal separator

    if(strlen($FormatArray[9]))
    {
      $NumberValue = str_replace("<FONT COLOR=\"" . $FormatArray[9] . "\">", "", $NumberValue);
      $NumberValue = str_replace("</FONT>", "", $NumberValue);
    }
  }
  $NumberValue = str_replace(",", ".", $NumberValue); // Decimal separator

  return $NumberValue;
}
//End CCCleanNumber

//CCParseInteger @0-FDF2EE85
function CCParseInteger($NumberValue, $FormatArray)
{
  return CCParseNumber($NumberValue, $FormatArray, ccsInteger);
}
//End CCParseInteger

//CCParseFloat @0-C9EAEA95
function CCParseFloat($NumberValue, $FormatArray)
{
  return CCParseNumber($NumberValue, $FormatArray, ccsFloat);
}
//End CCParseFloat

//CCValidateBoolean @0-7BAB2020
function CCValidateBoolean($BooleanValue, $Format)
{
  $Result = true;

  if(is_array($Format))
  {
    if(strtolower($BooleanValue) != strtolower($Format[0]) 
      && strtolower($BooleanValue) != strtolower($Format[1]) 
      && strtolower($BooleanValue) != strtolower($Format[2])
    )
      $Result = false;
  }

  return $Result;
}
//End CCValidateBoolean

//CCFormatBoolean @0-5B3F5CF9
function CCFormatBoolean($BooleanValue, $Format)
{
  $Result = $BooleanValue;

  if(is_array($Format))
  {
    if($BooleanValue == 1)
      $Result = $Format[0];
    else if(strval($BooleanValue) == "0" || $BooleanValue === false)
      $Result = $Format[1];
    else
      $Result = $Format[2];
  }

  return $Result;
}
//End CCFormatBoolean

//CCParseBoolean @0-0C7716BC
function CCParseBoolean($Value, $Format)
{
  $Result = $Value;
  if(is_array($Format))
  {
    if(strtolower(strval($Value)) == strtolower(strval($Format[0])))
      $Result = true;
    else if(strtolower(strval($Value)) == strtolower(strval($Format[1])))
      $Result = false;
    else
      $Result = "";
  }
  return $Result;
}
//End CCParseBoolean

//CCGetBooleanFormat @0-B9D3DA0C
function CCGetBooleanFormat($Format)
{
  $FormatString = "";
  if(is_array($Format))
  {
    for($i = 0; $i < sizeof($Format); $i++) {
      if(strlen($Format[$i])) {
        if(strlen($FormatString)) $FormatString .= ";";
        $FormatString .= $Format[$i];
      }
    }
  }
  return $FormatString;
}
//End CCGetBooleanFormat

//CCCompareValues @0-C648DC26
function CCCompareValues($Value1,$Value2,$DataType = ccsText, $Format = "")
{
  switch ($DataType) {
    case ccsInteger:
    case ccsFloat:
      if(strcmp(trim($Value1),"") == 0 || strcmp(trim($Value2),"") == 0)
        return strcmp($Value1, $Value2);
      else if($Value1 > $Value2)
        return 1;
      else if($Value1 < $Value2)
        return -1;
      else
        return 0;
  
    case ccsText:
    case ccsMemo:
      return strcmp($Value1,$Value2);

    case ccsBoolean:
      if (is_bool($Value1))
        $val1=$Value1;
      else if (strlen($Value1)!= 0 && CCValidateBoolean($Value1,$Format))
        $val1=CCParseBoolean($Value1,$Format);
      else 
        return 1;

      if (is_bool($Value2))
        $val2=$Value2;
      else if (strlen($Value2)!= 0 && CCValidateBoolean($Value2,$Format))
        $val2=CCParseBoolean($Value2,$Format);
      else 
        return 1;

      return $val1 xor $val2;
  
    case ccsDate:
      if(is_array($Value1) && is_array($Value2)) {
        if($Value1[ccsTimestamp] && $Value2[ccsTimestamp]) {
          if ($Value1[ccsTimestamp] > $Value2[ccsTimestamp])
            return 1;
          else if($Value1[ccsTimestamp] < $Value2[ccsTimestamp])
            return -1;
          else 
            return 0;
        } else {
          if(count(array_diff($Value1, $Value2)))
            return 1;
          else 
            return 0;
        }
      } else if(is_array($Value1)) {
        $FormattedValue = CCFormatValue($Value1, $Format, $DataType);
        return CCCompareValues($FormattedValue, $Value2);
      } else if(is_array($Value2)) {
        $FormattedValue = CCFormatValue($Value2, $Format, $DataType);
        return CCCompareValues($Value1,$FormattedValue);
      } else {
        return CCCompareValues($Value1,$Value2);
      }
    
  }
}
//End CCCompareValues

//CCConvertEncoding @0-5177A761
function CCConvertEncoding($text, $from, $to)
{
    return $text;
}
//End CCConvertEncoding

//CCConvertEncodingArray @0-1F8BB9EF
function CCConvertEncodingArray($array, $from="", $to="")
{
    if (strlen($from) && strlen($to) && strcmp($from, $to)) {
        while (list($key, $value) = each($array))
            $array[$key] = CCConvertEncoding($value, $from, $to);
    }
    return $array;
}
//End CCConvertEncodingArray

//CCConvertDataArrays @0-C39195DA
function CCConvertDataArrays($from="", $to="")
{
    global $FileEncoding;
    global $TemplateEncoding;
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    if ($from == "")
        $from = $TemplateEncoding;
    if ($to == "")
        $to = $FileEncoding;
    if (strlen($from) && strlen($to) && strcmp($from, $to)) {
        $HTTP_POST_VARS = CCConvertEncodingArray($HTTP_POST_VARS, $from, $to);
        $HTTP_GET_VARS = CCConvertEncodingArray($HTTP_GET_VARS, $from, $to);
    }
}
//End CCConvertDataArrays

//CCCheckSSL @0-F72881AB
function CCCheckSSL()
{
    $HTTPS = strtolower(getenv("HTTPS"));
    if($HTTPS != "on")
    {
        echo "SSL connection error. This page can be accessed only via secured connection.";
        exit;
    }
}
//End CCCheckSSL


?>
