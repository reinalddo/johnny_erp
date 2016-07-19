<?php
/*
*   Validacion  General de Ruc
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @output     Lista de Inconsistencias
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ComExCCS.php");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("../LibPhp/MisRuc.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//
    $alSql[] = "SELECT per_CodAuxiliar,
					concat(per_Apellidos, ' ', per_Nombres) as txt_Descripcion,
					case per_TipoID
						when 1 then 2
						when 2 then 1
					else 1
					end as per_TipoID,
					per_Ruc
				FROM conpersonas
				WHERE per_codAuxiliar <> 0
				" . (($pQry) ? " AND " . $pQry : " " );

    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}


//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//

    $db = NewADOConnection(DBTYPE);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
    $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);
    $pQry = rawurldecode(fGetParam("pQry", ''));    // texto de evaluacion de la condicion base (LIKE + el contenido de esta variable)
    $pLim = rawurldecode(fGetParam('pLim', 10));
    $pMax = rawurldecode(fGetParam('pMax', 10));
    set_time_limit (0) ;
	$rs= fDefineQry($db, $pQry);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    $rs->MoveFirst();
    $recno=0;
    $ilFields = $rs->FieldCount();
    $txt="";
    $i=0;
    echo "<table border='1' style='border: 1 solid'>";
    echo "<tr><td>CODIG<td >NOMBRE<td>RUC<td>TIPO ID <td>OBSERVACIONES <td></tr>";
    $aOut= array();
    while ($record =$rs->FetchRow()) {
//    	print_r($record);
        // -----------------                    DATA RECORD TO PROCESS
		$iRet = fValidaRuc($record['per_Ruc'], $record['per_TipoID']);
		if ($iRet < 0) {
	        echo "<tr>";
    	    echo "<td>";

			$aOut[$i]['COD'] = $record['per_CodAuxiliar'];
			echo $aOut[$i]['COD'] . "<td>";
			$aOut[$i]['NOMBRE'] = $record['txt_Descripcion'];
	        echo $aOut[$i]['NOMBRE'] . "<td>";
			$aOut[$i]['RUC'] = $record['per_Ruc'];
			echo  $aOut[$i]['RUC'] . "<td>";
			$aOut[$i]['TIPO'] = $record['per_TipoID'];
			echo $aOut[$i]['TIPO'] . "<td>";
			switch ($iRet) {
			    case -2:
			        $aOut[$i]['OBS'] = 'Digitos 1 y 2 Invalidos';
			        break;
				case -3:
				    $aOut[$i]['OBS'] = 'Digito 3 Invalido';
				    break;
                case -13:
				    $aOut[$i]['OBS'] = 'Longitud Invalida';
				    break;
				case -99:
					$aOut[$i]['OBS'] = 'Digito verificador Invalido';
					break;
				case -90:
					$aOut[$i]['OBS'] = 'Tipo de ID Invalido';
					break;
				default:
				    $aOut[$i]['OBS'] = 'posicion ' . $iRet .' Invalida';
			}
			echo $aOut[$i]['OBS'] . "<td>";
			$i++;
			echo "</tr>";
		}
    }
    echo "</table> <br>------ " . $i . " registros";
//    print_r($database);
//    return suggestions($text, $database);
?>
