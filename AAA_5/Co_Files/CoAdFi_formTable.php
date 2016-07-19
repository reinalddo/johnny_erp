<?
/*
    COMEX
    Ingresar Préstamos a Productores
*/
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include"../De_Files/DeGeGe_cabecera.php";

//include_once "../LibJs/ext/ux/Ext.ux.grid.GridSummary.html";

header("Content-Type: text/html;  charset=ISO-8859-1");
//$loadJSFile = "../LibJs/ext3/ux/Ext.ux.TableLayout.js";

$_SESSION[$gsSesVar . '_defs']['tra_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['tra_Fecha'] = '@date("Y-m-d H:i:s")';

// Para enviar a Js las variables de sesion en php
// usuario, ip local de la maquina
echo '<script languaje="JavaScript">
         var ses_usuario="'.$_SESSION['g_user'].'";
         var ses_ip="'.$_SESSION['g_ip'].'";
      </script>';


$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - PAGOS A PRODUCTORES";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");

$loadJSFile='../CoAdFi_formTable'; 
//$loadJSFile='../basic-print.js';
$db->debug = fGetParam('pAdoDbg', false);

$gsSesVar = "genTransac";
//Prefijo de Id se variable de sesion.
$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids

if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
$glExpanded = fGetParam('pExpan', 1);
$_SESSION[$gsSesVar. "_aplic"]= "";
/*cat_Categoria = 53 es para la categoría proveedor
  cat_Categoria = 52 es para productores */                                
$_SESSION["CoAdFi_productor"] = "select per_CodAuxiliar cod,  concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres) txt
                                ,pa.par_valor2 tipo, per_Ruc ruc
                                from conpersonas per
                                inner JOIN concategorias cat on
                                        cat.cat_CodAuxiliar = per.per_CodAuxiliar
                                        and cat.cat_Categoria = 52
                                inner join genparametros pa on
                                        pa.par_clave= 'TIPID' and pa.par_secuencia = per.per_tipoID
                                where upper(concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres)) LIKE upper('%{query}%')
                                order by per_apellidos,per_nombres";
                                
$_SESSION["CoAdFi_transaccion"] = "select par_Secuencia cod, concat(par_Secuencia,' ',par_Descripcion) txt,
                                par_Valor1 planPago, par_Valor3 maxCuotas    
                                from genparametros
                                where par_clave = 'CLIBRO'
                                AND PAR_VALOR4 = 'COMEX'
                                and upper(concat(par_Secuencia,' ',par_Descripcion)) LIKE upper('%{query}%')
                                order by par_Secuencia";
                                
$_SESSION["CoAdFi_estados"] = "select par_Valor1 cod, concat(par_Valor1,' ',par_Descripcion) txt
                                from genparametros
                                where par_clave = 'CADEST'
                                AND PAR_VALOR4 = 'COMEX'
                                and concat(par_Valor1,' ',par_Descripcion) LIKE '{query}%'
                                order by par_Valor1";
// CoAdFi_estadosAp trae los estados disponibles para aprobar
$_SESSION["CoAdFi_estadosAp"] = "select par_Valor1 cod, concat(par_Valor1,' ',par_Descripcion) txt
                                from genparametros
                                where par_clave = 'CADEST'
                                AND PAR_VALOR4 = 'COMEX'
                                and par_VAlor1 not in(1,3)
                                and concat(par_Valor1,' ',par_Descripcion) LIKE '{query}%'
                                order by par_Valor1";
                                
// para el store que carga los datos del formulario:
$_SESSION["CoAdFi_StoreTrans"] = "select tra_Id
		    ,tra_Emisor
		    ,tra_Receptor
		    ,tra_Concepto
		    ,tra_RefOperat
		    ,tra_Motivo
		    ,date_format(tra_Fecha, '%Y-%m-%d %H:%i') tra_Fecha
		    ,tra_Cuotas
		    ,tra_Valor
		    ,tra_Estado
		    ,tra_Usuario
		    ,tra_Semana
                    ,par_Descripcion
		    ,par_Valor1 as 'planPago'
		    ,par_Valor3 as 'maxCuotas'
                    from  genTransac
		    join genparametros on par_clave = 'CLIBRO' AND PAR_VALOR4 = 'COMEX' and par_Secuencia = tra_Motivo
	    where tra_ID = {cnt_ID}";
            
$_SESSION["CoAdFi_StoreDet"] = "SELECT tra_Id
                                ,tra_Cuota
                                ,tra_Valor
                                ,date_format(tra_Fecha, '%Y-%m-%d %H:%i') tra_Fecha
                                ,tra_Saldo
                                ,tra_Estado
                                ,date_format(tra_Fecha_vence, '%Y-%m-%d %H:%i') tra_Fecha_vence
                                ,date_format(tra_Fecha_ult_abono, '%Y-%m-%d %H:%i') tra_Fecha_ult_abono
                                ,tra_Usuario
                                ,tra_Semana
                                from genTransacDetal where tra_ID = {ID}";
                                
// Para verificar el numero de cuotas al contabilizar:
$_SESSION["CoAdFi_CuotasAp"] = "SELECT par_Valor1 planpago
                                        ,par_Valor3 maxcuotas
					,tra.tra_Cuotas cuotastra
                                        ,COUNT(det.tra_Cuota) cuotasdet
                                FROM genTransac tra
                                LEFT JOIN genTransacDetal det ON det.tra_Id = tra.tra_Id
                                JOIN genparametros ON par_clave = 'CLIBRO' AND PAR_VALOR4 = 'COMEX' AND par_Secuencia = tra.tra_Motivo
                                WHERE tra.tra_Id = {ID}";
                        
$goPanel->render();

?>