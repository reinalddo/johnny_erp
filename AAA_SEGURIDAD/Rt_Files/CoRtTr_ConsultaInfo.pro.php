<?php
/*
*   Para generar consultas
*   @author     Erika Suarez
*/
error_reporting(E_ALL);
include("../LibPhp/ComExCCS.php");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");

function &fConsFormRete(&$db){
    /**
     *	Consulta el Formulario de retencion que se encuentra en:
     *	Parametros Generales - Empresa - EGFRE Formato de Retencion
     */
    $alSql = "";
    $alSql  = "SELECT IFNULL(
			( SELECT CONCAT(TRIM(IFNULL(par_Valor1,'')),TRIM(IFNULL(par_Valor2,'')),TRIM(IFNULL(par_Valor3,'')),TRIM(IFNULL(par_Valor4,'')))  
			FROM genparametros WHERE par_Clave = 'EGFRE'
		),'../Rt_Files/CoRtTr_comprob_Matricial.rpt.php') AS Formulario
		";    
        
    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTO LA CONSULTA: " . $alSql[0]);
    return $rs;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
    $db = NewADOConnection(DBTYPE);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
    $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);
    
    $pProc = rawurldecode(fGetParam("pProc", 1));
    $pLim = rawurldecode(fGetParam('pLim', 10));
    $pMax = rawurldecode(fGetParam('pMax', 10));
    
    if ($pProc == 1){ /*Consultar Formulario de Retencion*/
	$rs= fConsFormRete($db);
	if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    
	$rs->MoveFirst();
	$Form="";
	while ($record =$rs->FetchRow()) {
	    $Form = $record['Formulario'];
	}    
	    echo  $Form;
    }
    
?>
