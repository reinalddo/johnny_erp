<?
/*
 * PANEL DE REPORTES PARA LIQUIDACION -FRUTIBONI
 */
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include"../De_Files/DeGeGe_cabecera.php";

header("Content-Type: text/html;  charset=utf-8");

$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - REPORTES DE LIQUIDACION";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../../AAA_5/LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$loadJSFile='../LiLiRp_panelReportes';

$db->debug = fGetParam('pAdoDbg', false);

$gsSesVar = "LiReportes";
 

$_SESSION["LiLiRp_Productores"] = "select   per_CodAuxiliar cod
                                            ,concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres) txt
                                    from conpersonas per
                                    inner JOIN concategorias cat on
                                             cat.cat_CodAuxiliar = per.per_CodAuxiliar
                                             and cat.cat_Categoria = 52
                                    inner join genparametros pa on
                                             pa.par_clave= 'TIPID' and pa.par_secuencia = per.per_tipoID
                                    WHERE (CONCAT(UPPER(per_CodAuxiliar),' ',UPPER(per_apellidos),' ',UPPER(per_nombres))) LIKE UPPER('%{query}%')
                                    order by per_apellidos,per_nombres";
                                    
                                    
$_SESSION["LiLiRp_TipoComision"] = " SELECT  par_Secuencia AS cod, par_Descripcion AS txt
                                            ,par_Valor1 AS ReqAux, par_valor2 AS ReqCajas, par_Valor3 AS ReqTpCja 
                                    FROM genparametros
                                    WHERE par_Clave = 'LGCOST'
                                    and UPPER(par_Descripcion) LIKE UPPER('%{query}%')
                                    order by par_Descripcion";
                                    
$_SESSION["LiLiRp_Comisionista"] = " SELECT per_CodAuxiliar AS cod, CONCAT(per_Apellidos,' ',per_Nombres) AS txt FROM conpersonas 
                                    JOIN concategorias ON cat_CodAuxiliar = per_CodAuxiliar AND cat_Categoria = 89 /*COMISIONISTAS*/ AND cat_Activo = 1 /*solo los de estado activo*/
                                    WHERE CONCAT(UPPER(per_Apellidos),' ',UPPER(per_Nombres)) LIKE UPPER('%{query}%')
                                    order by per_apellidos,per_nombres";
                                    
$_SESSION["LiLiRp_ComisionSemanas"] = " SELECT liquidacionDatoExtra.*
                                                ,CONCAT(per_Apellidos,' ',per_Nombres) AS Txauxiliar
                                                ,par_Descripcion AS TxtipoVariable
                                                ,par_Valor1 ReqCajas
                                                ,CASE par_Valor1
                                                        WHEN 1 THEN lde_cajas*lde_precio
                                                        WHEN 0 THEN lde_precio
                                                END AS PrecioTotal
                                        FROM liquidacionDatoExtra 
                                        LEFT JOIN genparametros ON par_Clave = 'LGCOST' AND par_Secuencia = lde_tipoVariable
                                        LEFT JOIN conpersonas ON per_CodAuxiliar = lde_auxiliar
                                        WHERE lde_estado = 1 AND lde_semana = {semana}";

$_SESSION["LiLiRp_ComisionEspec"] = " SELECT liquidacionDatoExtra.*
                                                ,CONCAT(per_Apellidos,' ',per_Nombres) AS Txauxiliar
                                                ,par_Descripcion AS TxtipoVariable
                                                ,par_Valor1 AS ReqAux, par_valor2 AS ReqCajas, par_Valor3 AS ReqTpCja
                                        FROM liquidacionDatoExtra 
                                        LEFT JOIN genparametros ON par_Clave = 'LGCOST' AND par_Secuencia = lde_tipoVariable
                                        LEFT JOIN conpersonas ON per_CodAuxiliar = lde_auxiliar
                                        WHERE lde_estado = 1 AND lde_id = {IDCom}";
                                    

$goPanel->render();
?>