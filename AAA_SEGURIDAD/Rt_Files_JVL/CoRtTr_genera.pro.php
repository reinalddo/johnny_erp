<?php
/*
*   Generacion de Anexo deretencionesformato 2005
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @output     Archivo de texto
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ComExCCS.php");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
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
    $alSql[] = "SELECT
					'0991312374001' as tmp_Decla,
					case length(per_RUC)
						WHEN 13 THEN per_RUC
						WHEN 12 THEN concat('0',   per_RUC)
						WHEN 11 THEN concat('00',  per_RUC)
						WHEN 10 THEN concat('000', per_RUC)
						ELSE '0000000000000'
					end AS tmp_Ruc	,
					CASE per_TipoID
						WHEN 1 THEN 2
						WHEN 2 THEN 1
						ELSE 0
					END AS per_TipoID,
					sum(tmp_BaseImp) tmp_Base,
					sum(tmp_Valreten) tmp_Reten,
					par_Valor1 as tmp_CodRetenc,
					date_format(com_fecContab,'%m%Y') as tmp_Perio,
					count(*) as tmp_Cuent
					FROM fistransac
						LEFT JOIN conpersonas on per_codauxiliar = tmp_codauxiliar
						LEFT JOIN genparametros ON par_clave = 'CRTFTE' and par_secuencia =  tmp_Codretenc
					WHERE com_feccontab between '" . rawurldecode(fGetParam("pDesde", '')) .
						"' AND '" . rawurldecode(fGetParam("pHasta", '')) . "'
					GROUP BY  1,2,3,6
					ORDER BY  1,2";
//    $alSql[0] .= ($pQry ? " WHERE " . $pQry  : " " ) . " ORDER BY 1, 2";
    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}


function fGeneraArchivo($pFile='RECO', $pCont) {
        $dir = '../pdf_files';
        //save the file
        $w = 0 ;
        if (!file_exists($dir)){
            mkdir ($dir,0777);
        }
        mt_srand();
        $fname = $dir . "/" . $pFile . mt_rand(1,5000) . '.ANE';
        $fp = fopen($fname,'w');
        fwrite($fp,$pCont);
        fclose($fp);
        return $fname;
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
	$rs= fDefineQry($db);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    $rs->MoveFirst();
    $slQry   = fGetParam('pQryLiq', false);
    $recno=0;
    $ilFields = $rs->FieldCount();
    $txt="";
    while ($record =$rs->FetchRow()) {
//    	print_r($record);
        // -----------------                    DATA RECORD TO PROCESS
        if ($recno >0)	$txt .= "\r\n";
        $txt .= CCFormatNumber($record['tmp_Decla'],  Array(True, 0, "", "", False, Array("0","0","0","0","0","0","0","0","0","0","0","0","0"), Array(), 1, True, ""));
        $txt .= $record['tmp_Ruc'];
		$txt .= $record['per_TipoID'];
		$txt .= CCFormatNumber($record['tmp_Base'],  Array(True, 2, ".", "", False, Array("0","0","0","0","0","0","0","0"), Array("0", "0"), 1, True, ""));
		$txt .= CCFormatNumber($record['tmp_Reten'], Array(True, 2, ".", "", False, Array("0","0","0","0","0","0"), Array("0", "0"), 1, True, ""));
		$txt .= $record['tmp_CodRetenc'];
		$txt .= $record['tmp_Perio'];
		$txt .= CCFormatNumber($record['tmp_Cuent'],  Array(True, 0, ".", ",", False, Array("0", "0", "0"), Array(), 1, True, ""));
        $recno+=1;
    }
//    print_r($database);
//    return suggestions($text, $database);
	echo  "<a href=" . fGeneraArchivo("REOC". rawurldecode(fGetParam("pArchivo", '')), $txt) . "> Bajar Archivo generado </a>";

?>
