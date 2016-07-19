<?php
/** Proceso para Contabilizar
 *  Consumo de Inventario
**/

include ("../LibPhp/LibInc.php");
include("LiLiPr_func.inc.php");
include("../LibPhp/ConTranLib.php");


echo "
<!doctype html public '-//W3C//DTD HTML 4.0 //EN'>
<html>
<head>
    <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
    <META HTTP-EQUIV='Cache-Control' CONTENT='no-cache'>
    <title>CONSUMO DE INVENTARIO</title>
</head>
<body bgcolor='#fffff7' link='#000000' alink='#ff0000' vlink='#000000' text='#000000' style='PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 11px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0; font-size:10px' bottommargin='0' nowrap leftmargin='0' topmargin='17' rightmargin='0' marginwidth='0' marginheight='0'>
<style type='text/css'>
</style>
";

function fContabiliza($db,$ilSemIni,$ilSemFin){
    
    echo("<br>".$_SESSION["g_empr"]."<br>");    
    echo("Semana de Inicio: ".$ilSemIni."<br> Semana Fin: ".$ilSemFin."<br>");
    
    // SELECCIONAR BASE DE LIQUIDACION
    $slSql = "SELECT * FROM genparametros WHERE par_Clave = 'LDATO' and par_Secuencia = 1";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('ATENCION','NO SE SELECCIONO BASE DE LIQUIDACION', true,false);
    $r = $rsLiq->fetchRow(); 
    $baseLiq = $r['par_Valor1'];
    //echo($baseLiq);
    //echo($_SESSION["g_dbase"]);
    
    // ELIMINAR COMPROBANTES PARA EVITAR DUPLICADOS
    IF(!$db->Execute( "DELETE FROM concomprobantes 
                       WHERE com_tipocomp = 'LE'
                       AND com_RefOperat BETWEEN ".$ilSemIni." AND ".$ilSemFin)) fErrorPage('','NO SE PUDO ELIMINAR DATOS PRELIMINARES', true,false);
    
    // INSERTAR CABECERA DE COMPROBANTE
    $slSql = " ";
    $slSql = "INSERT INTO concomprobantes 
                    (com_CodEmpresa, 
                    com_Establecimie, 
                    com_PtoEmision, 
                    com_TipoComp, 
                    com_NumComp, 
                    com_FecTrans, 
                    com_FecContab, 
                    com_FecVencim, 
                    com_emisor, 
                    com_CodReceptor, 
                    com_Receptor, 
                    com_Concepto, 
                    com_Valor, 
                    com_TipoCambio, 
                    com_Libro, 
                    com_NumRetenc, 
                    com_RefOperat, 
                    com_EstProceso, 
                    com_EstOperacion, 
                    com_NumProceso, 
                    com_CodMoneda, 
                    com_Usuario, 
                    com_NumPeriodo, 
                    com_PerOperativo, 
                    com_Vendedor, 
                    com_TsaImpuestos, 
                    com_FecDigita
                    )
                    SELECT DISTINCT DATABASE(),
                    '0', 
                    '0', 
                    'LE', 
                    tin_Semana, 
                    per_FecFinal, 
                    per_FecFinal, 
                    per_FecFinal, 
                    0, 
                    0, 
                    '-', 
                    CONCAT('CONSUMO SEMANA ' , tin_semana), 
                    0, 
                    1,
                    9999, 
                    0, 
                    tin_Semana, 
                    5, 
                    0, 
                    0, 
                    593, 
                    /*'fah',*/
                    '".$_SESSION["g_user"]."',
                    per_PerContable, 
                    0, 
                    0, 
                    1, 
                    NOW()
            FROM ".$baseLiq.".v_opetarjinsumos
            JOIN ".$baseLiq.".conperiodos ON per_aplicacion = 'LI' AND per_numperiodo = tin_semana
            WHERE tin_codempresa = ";
    if($_SESSION['g_dbase'] == $baseLiq ) $slSql .= "0"; else $slSql .= "DATABASE()";
    $slSql .= " AND tin_semana BETWEEN ".$ilSemIni." AND ".$ilSemFin;
    IF(!$db->Execute($slSql)) fErrorPage('','NO SE PUDO INSERTAR LA CABECERA DEL COMPROBANTE', true,false);
    
    
    // AJUSTAR PERIODOS SEGUN FECHAS 
    IF(!$db->Execute("UPDATE concomprobantes JOIN conperiodos ON per_aplicacion = 'CO' AND com_feccontab BETWEEN per_FecInicial AND per_fecfinal
                       SET com_NumPeriodo = per_NumPeriodo
                       WHERE com_tipocomp ='LE'")) fErrorPage('','NO SE PUDO ACTUALIZAR PERIODO A COMPROBANTE', true,false);
    
    
    // GENERAR DETALLE DE INVENTARIO
    $slSql = " ";
    $slSql = "INSERT INTO invdetalle	
        SELECT  com_CodEmpresa,
                com_regnumero,
                /* tin_Coditem*/ NULL,
                '-',
                tin_CodItem,
                tin_Cantidad,
                act_uniMedida,
                tin_Cantidad,
                0,
                0,
                0,  
                tin_semana,
                0,
                0,
                0,
                0,
                0,
                0,
                0
        FROM 	(   SELECT `tin_CodEmpresa`,
                             tin_Semana,
                            tin_CodItem,
                            SUM(tin_cantidad) AS `tin_Cantidad`
                            
                    FROM ".$baseLiq.".v_opetarjinsumos	
                    WHERE tin_codempresa = ";
    if($_SESSION['g_dbase'] == $baseLiq ) $slSql .= "0"; else $slSql .= "DATABASE()";
    $slSql .= "     AND tin_semana BETWEEN ".$ilSemIni." AND ".$ilSemFin."
                    GROUP BY 1,2,3
                ) tmp_data 			
                JOIN concomprobantes ON com_tipocomp ='LE' AND com_refoperat = tin_semana  AND com_codempresa = /* tin_codempresa */ DATABASE()
                JOIN conactivos ON act_codauxiliar = tin_coditem";
    IF(!$db->Execute($slSql)) fErrorPage('','NO SE PUDO INSERTAR DETALLE DEL COMPROBANTE', true,false);


    
}





/*---------------------------------------------------------------------------------------------------------------------------------------------
    Procesamiento
---------------------------------------------------------------------------------------------------------------------------------------------*/
$inicio = microtime();
$txt = "<br> Inicio&nbsp;&nbsp;&nbsp;&nbsp;: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
if(isset($_SESSION["g_user"])) $slUsuario=$_SESSION["g_user"];
$ilSemIni=fGetParam('pIni',0);
$ilSemFin=fGetParam('pFin',0);

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; 
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug=fGetParam('pAdoDbg', 0);
//fDesBloquea($db);
set_time_limit (0) ;

    if($ilSemIni <= 0 or $ilSemFin <=0) fErrorPage('','INGRESE SEMANA DE INICIO Y FIN', true,false);
    fContabiliza($db, $ilSemIni, $ilSemFin);

    echo "<br>CONTABILIZACION DE CONSUMO DE INVENTARIO  :</font></td></tr><tr><td>";
    $gsTitGeneral="";
    $final = microtime();
    $db->Close();
?>
    
    </tr>    <tr> <td align='CENTER' style='font-size:11px'> </td>  </tr>
    </table>
<?
$txt .= "<br> Finalizo: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
$txt .= "<br> TIEMPO UTILIZADO:      ".
        round(microtime_diff($inicio ,$final),2) . " segs. <br>";
echo "<center> $txt <br> PROCESAMIENTO OK </center>";
$txt.= " ." ;
?>
</body>
</html>




