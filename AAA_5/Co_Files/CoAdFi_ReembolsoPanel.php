<?
/* Panel para la administracion de reembolso por gastos
   para empleados - solicitado por Frutiboni
   @author  Erika Suarez    2011-08-04
*/

if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include"../De_Files/DeGeGe_cabecera.php";
header("Content-Type: text/html;  charset=ISO-8859-1");

 

// Para enviar a Js las variables de sesion en php
// usuario, ip local de la maquina
echo '<script languaje="JavaScript">
         var ses_usuario="'.$_SESSION['g_user'].'";
         var ses_ip="'.$_SESSION['g_ip'].'";
      </script>';

$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - REEMBOLSO POR GASTOS";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");

$loadJSFile='../CoAdFi_ReembolsoPanel'; 
$db->debug = fGetParam('pAdoDbg', false);
$gsSesVar = "genReembolso";

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25; //Valores Operativos minimos para los grids

// Para la pantalla que relaciona centros de costos con auxiliares
$_SESSION["CoAdFi_Auxiliar"] = "SELECT DISTINCT usu_AuxId cod, CONCAT(usu_AuxId,'-',usu_Nombre) txt FROM seguridad.segusperfiles
                                JOIN seguridad.segusuario ON usp_IDusuario =usu_IDusuario
                                JOIN seguridad.segempresas ON emp_BaseDatos = '".$_SESSION['g_dbase']."'
                                WHERE usp_IDempresa = emp_IDempresa
                                AND usu_AuxId IS NOT NULL
                                and concat(usu_AuxId,'-',upper(usu_Nombre)) LIKE upper('%{query}%')
                                ORDER BY usu_Nombre ";
                                
$_SESSION["CoAdFi_CCosto"] =   "SELECT cc.Cue_CodCuenta cod,  CONCAT(UPPER(cpa2.cue_Descripcion),'/',UPPER(cpa.cue_Descripcion),'/',UPPER(cc.Cue_CodCuenta),'-',UPPER(cc.cue_Descripcion)) txt 
                                FROM concuentas cc 
                                LEFT JOIN concuentas cpa ON cpa.Cue_ID = cc.Cue_Padre
                                LEFT JOIN concuentas cpa2 ON cpa2.Cue_ID = cpa.Cue_Padre
                                WHERE cc.cue_Clase = 5 
                                AND cc.Cue_CodCuenta LIKE '6%' 
                                AND cc.cue_TipMovim = 1
                                and CONCAT(UPPER(cpa2.cue_Descripcion),'/',UPPER(cpa.cue_Descripcion),'/',UPPER(cc.Cue_CodCuenta),'-',UPPER(cc.cue_Descripcion)) LIKE upper('%{query}%')
                                ORDER BY cod  ";

$_SESSION["CoAdFi_CCxAux"] =   "SELECT 	cxa_CodCuenta,
                                        CONCAT(cpa2.cue_Descripcion,'/',cpadre.cue_Descripcion, '/', c.cue_Descripcion) AS cCuenta,
                                        cxa_codPersona, 
                                        cxa_estado
                                FROM conCuentaAuxiliar
                                JOIN concuentas c ON c.cue_codcuenta = cxa_CodCuenta
                                JOIN concuentas cpadre ON cpadre.cue_ID = c.cue_Padre
                                LEFT JOIN concuentas cpa2 ON cpa2.Cue_ID = cpadre.Cue_Padre
                                where cxa_codPersona = {cxa_codPersona} and cxa_estado = 1 ";
                                
// Para la pantalla en que se ingresa la solicitud de reembolso
$_SESSION["CoAdFi_CCostoxUsuario"] =   "SELECT ccA.cxa_CodCuenta as cod ,concat(ccA.cxa_CodCuenta,'-',upper(cc.cue_Descripcion)) as txt
                                        FROM conCuentaAuxiliar ccA
                                        JOIN concuentas cc ON cc.cue_codCuenta = ccA.cxa_CodCuenta
                                        JOIN seguridad.segusuario u ON cxa_CodPersona = usu_AuxId
                                        WHERE usu_login  = '".$_SESSION['g_user']."'
                                        and concat(ccA.cxa_CodCuenta,'-',upper(cc.cue_Descripcion)) LIKE upper('%{query}%')
                                        and ccA.cxa_estado = 1 
                                        ORDER BY cc.cue_Descripcion  ";
                                
$_SESSION["CoAdFi_TipoMov"] =   "SELECT per_CodAuxiliar AS cod, CONCAT(upper(per_CodAuxiliar),'-',UPPER(per_Apellidos), '-',UPPER(per_Nombres))AS txt FROM conpersonas
                                JOIN concategorias ON cat_CodAuxiliar = per_CodAuxiliar
                                WHERE cat_Categoria = 60
                                AND per_Zona = 90 /*Solo los que tengan zona 90-tipo RG*/
                                and CONCAT(upper(per_CodAuxiliar),'-',UPPER(per_Apellidos), '-',UPPER(per_Nombres)) LIKE upper('%{query}%')
                                ORDER BY CONCAT(UPPER(per_Apellidos), '-',UPPER(per_Nombres)) ";

$_SESSION["CoAdFi_EstRee"] =   "SELECT par_Valor1 AS cod, UPPER(par_Descripcion) AS txt
                                FROM genparametros WHERE par_Clave = 'CADEST' AND par_Valor4 = 'REEMB'
                                and UPPER(par_Descripcion) LIKE upper('%{query}%')
                                ORDER BY 1 ";

$_SESSION["CoAdFi_EstReeAp"] =  "SELECT par_Valor1 AS cod, UPPER(par_Descripcion) AS txt
                                FROM genparametros WHERE par_Clave = 'CADEST' AND par_Valor4 = 'REEMB'
                                and UPPER(par_Descripcion) LIKE upper('%{query}%')
                                and par_Valor1 not in ('1')
                                ORDER BY 1 ";
// Consultar cabecera del reembolso
$_SESSION["CoAdFi_ReeCabecera"] =  "SELECT 	ree_Id, 
                                                ree_Emisor, 
                                                ree_Concepto, 
                                                ree_RefOperat, 
                                                DATE_FORMAT(ree_Fecha,'%y-%m-%d') as ree_Fecha,  
                                                ree_Valor, 
                                                ree_Estado, 
                                                ree_Usuario, 
                                                ree_UsuAprueba, 
                                                ree_FecAprueba, 
                                                ree_TipoComp, 
                                                ree_NumComp, 
                                                ree_FechaRegistro
                                                FROM 
                                                conReembolso  
                                                where ree_Id  = {pree_Id} ";
                                                
                                                
// Consultar el grid con el detalle del reembolso
$_SESSION["CoAdFi_ReeDetalle"] =  "SELECT 	red_Id, 
                                                red_Sec, 
                                                red_Concepto, 
                                                red_MotivoCC, 
                                                red_Aux, 
                                                red_Valor, 
                                                red_Usuario
                                                 
                                                FROM 
                                                conReembolsoDetal 
                                                where red_Id = {pree_Id}
                                                ORDER BY red_Sec ";


$goPanel->render();


?>