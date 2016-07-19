<?php

//header("Content-Type: text/plain");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");

/**     Presenta un grid con resultado de consulta
*       @access     public
*       @param      int      $ilTipo            Tipo de datos que se presentaran
*       @param      int      $ilNumproceso      Numero del proceso en ejecucion
*       @return     void
**/
function fPresentaGrid($ilTipo, $ilNumProces){
        $qStr=  "SELECT concat(left(per_Apellidos, 15), '  ' ,left(per_Nombres,12)),
                        tad_numtarja as TARJA,
                        tad_secuencia as SEC,
                        caj_Descripcion as CAJA,
                        (tad_CantRecibida - tad_CantRechazada  )  as EMBARCADO,
                        (tad_CantRecibida - tad_CantRechazada  )  * tad_valunitario AS 'PREC. OFICIAL' ,
                        (tad_CantRecibida - tad_CantRechazada  )  * tad_difunitario as ' ADELANTO'
                FROM ((liqtarjadetal join  liqtarjacabec on tar_NUmTarja = tad_Numtarja)
                JOIN conpersonas  on per_CodAuxiliar = tac_embarcador )
                JOIN liqcajas on caj_CodCaja = tad_CodCaja
                WHERE tad_LiqProceso = " . $ilNumProces .
                " ORDER BY 1, 2, 3 ";
 
}
/*----------------------INICIO
*/
        
$db = NewADOConnection("mysql");
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
$ilLista=('pLid', '0');
$slSql= "SELECT  distinct tad_NumTarja, tad_Secuencia " .
            "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa) " .
                                 "JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) ".
                                 "LEFT JOIN genparametros ON par_Secuencia = tad_CodMarca ".
            "WHERE emb_AnoOperacion = " . fGetParam("pAno", "0") . "  AND " .
                   fGetParam("pSemana", "0") . " BETWEEN emb_SemInicio AND emb_SemTermino
                    AND tad_LisPrecio = " . $illista;
                   
$slCond =str_replace('|', '=', fGetParam("pQry", ""));
$slSql .= $slCond . " Order by 1, 2";
$result = $db->Execute($slSql);

if ($result) {
    echo "TARJAS INCLUIDAS EN ESTE PROCESO: <br>";
    while (!$result->EOF )
    {
//    	echo "\t" .$result->fields[0] . " - " . $result->fields[1] . "   : ";
      	$slSql = "UPDATE liqtarjadetal ".
                         "SET tad_ValUnitario = " . fGetParam("pUni", "0") . ", " .
      	                     "tad_DifUnitario = " . fGetParam("pDif", "0") . ", " .
      	                     "tad_LisPrecio   = " . fGetParam("pLid", "0") . " " .
      	                 "WHERE tad_NumTarja = ".$result->fields[0] . " AND ".
      	                         "tad_Secuencia = ".$result->fields[1];
//        echo $slSql . " ";      	
        if ($db->Execute($slSql)) fErrorPage('','NO SE PUDO ACTUALIZAR DETALLE DE TARJAS ', true, false);
//        else echo " ERR";
//        echo "<BR>";
      	$result->MoveNext();  	
    }
    fPresentagrid($ilLista);

    echo "<br>" . $result->RecordCount() . " Registros Modificados\n";
    }
else
     fErrorPage('','NO EXISTE TARJAS CON EL CRITERIO DE BUSQUEDA ESPECIFICADO',
                                  $_SERVER['HTTP_REFERER'] . '?' . $_SERVER['QUERY_STRING'], false);

$db->Close();
?>

