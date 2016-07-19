<?
/*
    BITACORA - INGRESAR DOCUMENTOS
    @rev	esl	07/12/2010	parametros de longitud para el campo bit_numDoc
*/
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include"../De_Files/DeGeGe_cabecera.php";

//include_once "../LibJs/ext/ux/Ext.ux.grid.GridSummary.html";

//header("Content-Type: text/html;  charset=ISO-8859-1");
header("Content-Type: text/html;  charset=utf-8");
//$loadJSFile = "../LibJs/ext3/ux/Ext.ux.TableLayout.js";

$_SESSION[$gsSesVar . '_defs']['tra_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['tra_Fecha'] = '@date("Y-m-d H:i:s")';

// Para enviar a Js las variables de sesion en php
// usuario, ip local de la maquina
echo '<script languaje="JavaScript">
         var ses_usuario="'.$_SESSION['g_user'].'";
      </script>';


$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - BITACORA DE DOCUMENTOS";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/extAutogrid");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.grid.printergrid_0.0.1");
$goPanel->addBodyScript("../LibJs/ext/ux/grid/GridFilters");
$goPanel->addBodyScript("../LibJs/ext/ux/grid/filter/Filter.js");
$loadJSFile='../BiTrTr_bitacoraIngresoDoc';

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
$_SESSION["BiTrTr_bitProveedor"] = "select   per_CodAuxiliar cod
                                            ,concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres) txt
                                            ,pa.par_valor2 tipo
                                            ,per_Ruc ruc
                                    from conpersonas per
                                    inner JOIN concategorias cat on
                                             cat.cat_CodAuxiliar = per.per_CodAuxiliar
                                             and cat.cat_Categoria = 53
                                    inner join genparametros pa on
                                             pa.par_clave= 'TIPID' and pa.par_secuencia = per.per_tipoID
                                    where upper(concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres)) LIKE upper('%{query}%')
                                    order by per_apellidos,per_nombres";
                                
$_SESSION["BiTrTr_bitTipoDoc"] = "select par_Valor1 cod
                                        ,concat(par_Valor1,' - ',par_Descripcion) txt
                                  from 09_base.genparametros WHERE par_Clave = 'BITDOC'
                                  and upper(concat(par_Valor1,' - ',par_Descripcion)) LIKE upper('%{query}%')";

$_SESSION["BiTrTr_bitmovimiento"] = "select par_Secuencia cod
                                        ,concat(par_Secuencia,' - ',par_Descripcion) txt
                                    from 09_base.genparametros WHERE par_Clave = 'BITMOV'
                                    and par_Valor1 > 0
                                    and upper(concat(par_Secuencia,' - ',par_Descripcion)) LIKE upper('%{query}%')
                                    order by par_Valor2";
                                    
$_SESSION["BiTrTr_bitmotivoRechazo"] = "select par_Secuencia cod
                                        ,concat(par_Secuencia,' - ',par_Descripcion) txt
                                    from 09_base.genparametros WHERE par_Clave = 'BITDEV'
                                    and upper(concat(par_Secuencia,' - ',par_Descripcion)) LIKE upper('%{query}%')";

//#esl	07/12/2010	parametros de longitud para el campo bit_numDoc
$_SESSION["BiTrTr_bitDatos"] = "SELECT par_Secuencia, par_Descripcion
                                           ,par_Valor1, par_Valor2, par_Valor3, par_Valor4			
                                    FROM 09_base.genparametros WHERE par_clave = 'BITDAT'
                                    AND par_Secuencia = ifnull({p_Secuencia},par_Secuencia)";

/*$_SESSION["BiTrTr_bitusuarios"] = "SELECT usu_login cod,usu_Nombre txt FROM seguridad.segusuario
                                    where upper(usu_Nombre) LIKE upper('%{query}%')
                                    ORDER BY usu_Nombre";*/
                                    
$_SESSION["BiTrTr_bitusuarios"] = "SELECT usu_login cod,usu_Nombre txt
                                    ,usp_IDperfil,usp_IDempresa
                                    FROM seguridad.segusuario
                                    JOIN seguridad.segusperfiles ON usp_IDusuario = usu_IDusuario and  usp_IDperfil IN ('BITRV','BITSR','BITJR','BITEM')
                                    where upper(usu_Nombre) LIKE upper('%{query}%')
                                    GROUP BY usu_login
                                    ORDER BY usu_Nombre";
                                    
$_SESSION["BiTrTr_bitRevisores"] = "SELECT usu_login cod,usu_Nombre txt
                                    ,usp_IDperfil,usp_IDempresa
                                    FROM seguridad.segusuario
                                    JOIN seguridad.segusperfiles ON usp_IDusuario = usu_IDusuario and  usp_IDperfil IN ('BITRV')
                                    where upper(usu_Nombre) LIKE upper('%{query}%')
                                    GROUP BY usu_login
                                    ORDER BY usu_Nombre";
                                    
                                    
$_SESSION["BiTrTr_bitEmpresas"] = "SELECT usp_IDempresa cod,upper(emp_Descripcion) txt, emp_BaseDatos base FROM seguridad.segusperfiles
                                    JOIN seguridad.segusuario ON usp_IDusuario = usu_IDusuario
                                    JOIN seguridad.segempresas ON emp_IDempresa = usp_IDempresa
                                    WHERE usu_login = '".$_SESSION['g_user']."'
                                    and upper(emp_Descripcion) LIKE upper('%{query}%')
                                    GROUP BY usp_IDempresa
                                    order by emp_Descripcion";
                                
            
// para el store del grid que carga los documentos ingresados:
$_SESSION["BiTrTr_bitTodosDoc"] = " select   cab.bit_empresa as bit_empresa
                                            ,cab.bit_tipoDoc as bit_tipoDoc
                                            ,cab.bit_secDoc as bit_secDoc
                                            ,cab.bit_emiDoc as bit_emiDoc
                                            ,cab.bit_numDoc as bit_numDoc
                                            ,CONCAT(cab.bit_idAux,' - ',per_Apellidos,' ',per_Nombres) AS AuxNombre
                                            ,DATE_FORMAT(cab.bit_fechaDoc,'%Y-%m-%d') AS bit_fechaDoc
                                            ,cab.bit_valor as bit_valor
                                            ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d %h:%i:%s') AS bit_fechaReg
                                            ,DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') AS FechaReg
                                            ,DATE_FORMAT(cab.bit_fechaReg,'%h:%i:%s %p') AS HoraReg
                                            ,cab.bit_usuarioActual as bit_usuarioActual
                                            ,cab.bit_idAux as bit_idAux
                                            ,cab.bit_registro as bit_registro
					    ,det.bit_secuencia AS maxSecuencia
					    ,det.bit_movimiento as bit_movimiento
					    ,par_Descripcion AS movimiento
                                            ,det.bit_observacion as bit_observacion
                                    FROM  bitacora cab
                                    JOIN bitacoradetalle det  ON det.bit_tipoDoc = cab.bit_tipoDoc 
							    AND det.bit_secDoc = cab.bit_secDoc
                                                            AND det.bit_emiDoc = cab.bit_emiDoc
                                                            AND det.bit_numDoc = cab.bit_numDoc
							    AND det.bit_idAux = cab.bit_idAux
                                                            and det.bit_registro = cab.bit_registro
							    AND  det.bit_secuencia = (SELECT MAX(d2.bit_secuencia) FROM bitacoradetalle d2  WHERE d2.bit_tipoDoc = det.bit_tipoDoc
										    AND d2.bit_secDoc = det.bit_secDoc
                                                                                    AND d2.bit_emiDoc = det.bit_emiDoc
                                                                                    AND d2.bit_numDoc = det.bit_numDoc
                                                                                    AND d2.bit_idAux = det.bit_idAux
                                                                                    AND d2.bit_registro = det.bit_registro)
                                    JOIN conpersonas ON per_CodAuxiliar = cab.bit_idAux
				    LEFT JOIN 09_base.genparametros ON par_Clave = 'BITMOV' AND par_Secuencia = det.bit_movimiento
				    /*movimiento 1 = RECEPCION*/
                                    WHERE det.bit_movimiento = 1
                                    and cab.bit_cerrado = 0
                                    /*DOCUMENTOS ANULADOS NO PUEDEN SER MODIFICADOS NI APARECEN EN NINGUN REPORTE*/
                                    and cab.bit_anulado = 0
                                    and cab.bit_usuarioActual = '".$_SESSION['g_user']."'
                                    and DATE_FORMAT(cab.bit_fechaReg,'%Y-%m-%d') between '{fecha_desde}' and '{fecha_hasta}'
                                    order by {sort} {dir} LIMIT {start}, {limit}";
                                    
// para el store del grid que carga la cabecera del documento a modificar:

$_SESSION["BiTrTr_bitDocumento"] = " select  bit_codempresa as bit_codempresa
                                            ,bit_tipoDoc as bit_tipoDoc
                                            ,bit_secDoc as bit_secDoc
                                            ,bit_emiDoc as bit_emiDoc
                                            ,bit_numDoc as bit_numDoc
                                            ,bit_idAux  as bit_idAux
                                            ,bit_registro as bit_registro
                                            ,DATE_FORMAT(bit_fechaDoc,'%Y-%m-%d') AS bit_fechaDoc
                                            ,bit_valor as bit_valor
                                            ,DATE_FORMAT(bit_fechaReg,'%Y-%m-%d %h:%i:%s') AS bit_fechaReg
                                            ,bit_usuarioActual as bit_usuarioActual
                                    FROM  bitacora
                                    /*DOCUMENTOS ANULADOS NO PUEDEN SER MODIFICADOS NI APARECEN EN NINGUN REPORTE*/
                                    where bit_anulado = 0
                                    and bit_tipoDoc = '{pbit_tipoDoc}' and bit_secDoc = '{pbit_secDoc}' and bit_emiDoc = '{pbit_emiDoc}' and bit_numDoc = '{pbit_numDoc}' and bit_idAux = {pbit_idAux} and bit_registro = {pbit_registro}";

$goPanel->render();

?>