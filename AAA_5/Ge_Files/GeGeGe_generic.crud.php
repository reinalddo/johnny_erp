<?php
/*
*   CRUD generico. El resultado se obtiene en fmto JSON
*   Modificado: 29-abr-2009
*/
/*
*   Definir la consulta que se ejcutara
*/
function fDefineSql($pTabla){
	global $db, $appStatus;
	$slSql = "";
	$slAction = fGetParam('pAction', false);
 	$olFields = $db->MetaColumns($pTabla);
 	$slDefs= $_SESSION[$pTabla . '_defs'];
//	print_r($olFields);
//	print_r($_GET);
	//print_r($_POST);
	//$slTipo = $db->MetaType($olField->type);
	switch ($slAction){
	    case "REP":
		case "ADD":
			$slValues = "";
			$slFields = "";
			$il = 0;
			$id =0;
			foreach ($olFields as $k => $olField) {
				$slTxt =  " : " . $olField->type . " / " . $slValue . "\n\r";
				fSetAppStat(10, 'I', $slTxt);
				if ($slAction == "ADD" && ($olField->auto_increment || (($olField->type == 'datestamp' || $olField->type == 'timestamp' ) ))) {  /* && !isset($slDefs[$olField->name])*/ 
					continue;
				} else {
					//echo "\n\r tipo: " . $olField->name . " / " . $olField->type . " -- " . $slTipo ."\n\r"; print_r($slTipo);
					//echo $slDefs[$olField->name];
					if (isset($slDefs[$olField->name])) {
						if (substr($slDefs[$olField->name],0,1) == "@") {
							$slValue =eval(substr($slDefs[$olField->name],1));
						} else {
							$slValue =$slDefs[$olField->name];
						}
					} else $slValue = fGetParam($olField->name, '@@noDef@');
					$slTxt =  " : " . $olField->type . " / " . $slValue . "\n\r";
					fSetAppStat(10, 'I', $slTxt);
					if ($slValue !='@@noDef@' ) {
					    $slValue = fParseValue($olField->type, $slValue);
					    $slFields .= ((strlen ($slFields)   >0) ? "," : "") . " " . $olField->name;
					    $slValues .= ((strlen ($slValues)>0) ? "," : "") . $slValue;
					}
			  }
					//			    echo $slSql ."\n";
			$il ++;
			}
			if ($il > 0) {
			  $slSql = (($slAction =="ADD") ? " INSERT " : " REPLACE " ) . " INTO $pTabla (" . $slFields . ") VALUES (" . $slValues . ")" ;
			}
//echo $slSql ."\n";
			break;
      case "UPD":
				$slValues = "";
				$il = 0;
				foreach ($olFields as $olField){
					$slValue = fGetParam($olField->name, '@@noDef@');
					if ($slValue !='@@noDef@' ) {
							$slValue = fParseValue($olField->type, $slValue);
							$slSql 	  .= ((strlen ($slSql) >0) ? "," : "") . " " .
									 $olField->name . " = " . $slValue ;
						$il ++;
					}
				}
				$slCond = fPrimaryKeyString($olFields,$pTabla);
				$slSql  = "UPDATE $pTabla SET " . $slSql . " WHERE  " . $slCond ;
				break;
      case "DEL":
				$slValues = "";
				$slCond = fPrimaryKeyString($olFields,$pTabla);
				$slSql .= "DELETE FROM $pTabla WHERE ". $slCond;
				break;
	}
//echo $slSql. " :::";
	return $slSql;
}

/*
*Fecha de Modificacion: 29-abr-2009
*Funcion fue modificada porque recorria todos los campos de tabla en busca de claves primarias
*$db->MetaPrimaryKeys($pTabla) esta funcion solo trae las claves primarias por lo que es mas practico de usar
*/
function fPrimaryKeyString($pFlds,$pTabla){
	global $db;
    $slKeyString = "";
    $olKeys = $db->MetaPrimaryKeys($pTabla);
    //print_r($pFlds);
   
    $limite = sizeof($olKeys);
    $cont = 0;
    while ($cont < $limite){
	$key = $olKeys[$cont];
	$olField = $pFlds[strtoupper($key)];
	$slValue = fGetParam($key, '@@noDef@');
	if ($slValue !='@@noDef@' ) {
		$slKeyString .= (strlen($slKeyString)? " AND " : " " ).
			$key . " = " . fParseValue($olField, $slValue);
	}
	$cont++;
    }
    //print($slKeyString);
    /*foreach ($pFlds as $k => $olField){
	    //print_r ($olField);
	    if ($olField->primary_key == 1){
			$slValue = fGetParam($olField->name, '@@noDef@');
			if ($slValue !='@@noDef@' ) {
				$slKeyString .= (strlen($slKeyString)? " AND " : " " ).
					$olField->name . " = " . fParseValue($olField, $slValue);
			}
	    }
	    //print($slKeyString);
    }*/
    return (strlen($slKeyString)? $slKeyString : false);
}


/*
*
*/
function fParsevalue($olField, $slValue){
	global $db, $appStatus;
  $slTxt =  " : " . $olField->name . " / " . $slValue . "\n\r";
	fSetAppStat(10, 'I', $slTxt);
	if(fGetParam('pAppDbg', false))  echo $olField->name . " - " . $olField->type . " : " . $slValue . " ---- " ;
	switch($olField){
		case 'char':
		case 'varchar':
		case 'C':
		case 'X':
		case 'C2':
		case 'X2':
				$slValue = "'" . addSlashes($slValue) . "'";
				break;
		case 'B':
		case 'blob':
				$slValue = "'" . $slValue . "'";
				break;
		case 'int':
		case 'integer':
		case 'smallint':
		case 'tinyint':
		case 'bigint':
		case 'bool':
		case 'I':
		case 'I1':
		case 'I2':
		case 'I4':
		case 'I8':
		case 'L':
				if (is_null($slValue)) $slValue = 0;
				else $slValue = intval($slValue);
				break;
		case 'R':
		case 'autonumeric':
		case 'auto':
				if (!is_null($slValue))
						$slValue = intval($slValue);
						//if ($slValue != intval($slValue)) $slValue = 0 ;
					break;
		case 'float':
		case 'bigfloat':
		case 'double':
		case 'decimal':
		case 'numeric':
		case 'F':
		case 'N':
				if (!is_numeric($slValue)) $slValue  = 0;
				break;
    case 'date':
		case 'D':
		case 'T':
				if (strlen($slValue) > 4) $slValue = "'" . fText2Fecha($slValue,'Y-m-d', 'd/M/Y') . "'";
				else $slValue = "'0000-00-00'";
				break;
    case 'datestamp':
    case 'datetime':
				list($slFech, $slHora) = split(' ',$slValue);
				if (strlen($slValue) > 4)
					$slValue = "'" . fText2Fecha($slFech,'Y-m-d', 'd/M/Y') . ' '. $slHora . "'";
				else $slValue = "'0000-00-00 00:00:00'";
				break;
		case 'timestamp':
		case 'time':
				if (strlen($slValue) > 4) $slValue = "'" . $slValue ."'";
				else $slValue = "'00:00'";
				break;
		default:
		    $slValue = "'" . $slValue . "'";
	}
	if(fGetParam('pAppDbg', false))  echo " convertido a : " . $slValue . "\n";
	return $slValue;
}
/*
*
*/
function fSetAppStat($pStat, $pType, $pMens="", $pCode =0, $pExpl=""){
	global $appStatus;
    $appStatus->setError($pStat, $pType, $pMens, $pCode , $pExpl);
}
/*
*
*/
function fGrabaBitacora(&$db, $pID, $pTxt=' '){
	global $pTabla;
	$slTxt =  fGetParam('pAction', '---') . ":  ". $pTxt;
    $slSql = "INSERT INTO segbitacora ( ".
                "bit_TipoObj, bit_NumeroObj, bit_anotacion, bit_CantRegis, ".
                "bit_Valor1,  bit_Valor2,    bit_autoriza,  bit_estado, ".
                "bit_IDusuario, bit_ModCodigo) ".
             "VALUES ( " . fGetParam($_SESSION[$pTabla . '_bitKey']) . ", " . $pID . ", '" .
			$slTxt . "',  0, " .
			 "0, 0, '-', 0, '" . $_SESSION['g_user'] . "', 0) ";
	$db->Execute($slSql);
//echo $slSql;
}
/*
*
*/
/*
*
*
*/
ob_start();
if (!isset ($_SESSION)) session_start();
include_once('General.inc.php');
include_once('GenUti.inc.php');
include_once('adodb.inc.php'); # load code common to ADOdb
include_once('adoConn.inc.php'); # load Common conection deffs
include_once('../LibPhp/JSON.php');
//
$rsDb=true;
$pTabla = fGetParam('pTabla', false);
if (!$pTabla) {
	echo "{success:false, msg: 'NO SE HA DEFINIDO TABLA'}";
	die();
}
$gsSql = fDefineSql($pTabla) ;
$rsDb=$db->Execute($gsSql);
header("Content-Type: text/javascript");
//
$olResp= NULL;
$olResp->data = array();
//
//print_r($db);
if ($rsDb){
	$olResp->success = true;
	$olResp->message="";
	$olResp->records=$db->Affected_Rows();
	$olResp->lastId =$db->Insert_ID();
//	echo $olResp->lastId;
	if ($_SESSION[$pTabla . '_bitID'])
		fGrabaBitacora($db, ($db->Insert_ID()?$db->Insert_ID():fGetParam($_SESSION[$pTabla . '_bitID'], -1)), " ");
	} else {
    $olResp->success = false;
    //$olResp->metaData= $db->MetaColumns($pTabla);
    $olResp->records=0;
		$olResp->lastId =0;
		$olResp->error  = $appStatus->getError('t');
		$olResp->message = $db->ErrorMsg();
		$olResp->sql  = $gsSql;
}
if (fGetParam('pAppDbg') == 2) {
    $olResp->sql  = $gsSql;
//    $olResp->status  = $appStatus;
	$olResp->message  = $appStatus->getError('t');
}
$json = new Services_JSON();
print($json->encode($olResp));
?>
