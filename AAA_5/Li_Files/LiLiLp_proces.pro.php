<?php
//header("Content-Type: text/plain");
session_start();
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");

$db = NewADOConnection("mysql");
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
//$db->Debug=true;
$slSql= "SELECT  distinct tad_NumTarja, tad_Secuencia " .
            "FROM ((liqembarques  JOIN liqtarjacabec ON tac_RefOperativa = emb_RefOperativa) " .
                                 "JOIN liqtarjadetal ON tad_NumTarja = tar_NumTarja) ".
                                 "LEFT JOIN genparametros ON par_Secuencia = tad_CodMarca ".
            "WHERE emb_AnoOperacion = " . fGetParam("pAno", "0") . "  AND " .
                   fGetParam("pSemana", "0") . " BETWEEN emb_SemInicio AND emb_SemTermino ";

$slCond =str_replace('|', '=', fGetParam("pQry", ""));
//echo "<br> CONDICION: $slCond ";

$slSql .= $slCond . " Order by 1, 2";

//echo $slSql;
$result = $db->Execute($slSql);

if ($result) {
    echo "MODIFICADO: <br>";
    while (!$result->EOF )
    {
    	echo "\t" .$result->fields[0] . " - " . $result->fields[1] . "   : ";
      	$slSql = "UPDATE liqtarjadetal ".
                         "SET tad_ValUnitario = " . fGetParam("pUni", "0") . ", " .
      	                     "tad_DifUnitario = " . fGetParam("pDif", "0") . ", " .
      	                     "tad_LisPrecio   = " . fGetParam("pLid", "0") . " " .
      	                 "WHERE tad_NumTarja = ".$result->fields[0] . " AND ".
      	                         "tad_Secuencia = ".$result->fields[1];
//        echo $slSql . " ";      	
        if ($db->Execute($slSql)) echo "OK";
        else echo " ERR";
        echo "<BR>";
      	$result->MoveNext();  	
    }

    echo "<br>" . $result->RecordCount() . " Registros Modificados\n";
    }
else
     fErrorPage('','NO EXISTE TARJAS CON EL CRITERIO DE BUSQUEDA ESPECIFICADO',
                                  $_SERVER['HTTP_REFERER'] . '?' . $_SERVER['QUERY_STRING'], false);

$db->Close();
?>



