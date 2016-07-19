<?
/*
*   Panel general de Tarjas
*   @created    13/Abr/09
*   @author     Gina Franco
*   @
*/
/************************************************************************* Inclusion de librerias bassicas */

if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include_once "../De_Files/DeGeGe_cabecera.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - TARJAS ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/ext/ux/grid/GridSummary");   
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addJsAfterScripts("window.title='" . $_SESSION['g_empre'] . "';");
//$goPanel->addBodyScript("OpTrTr_pruestr");                          // OPCIONAL:  Incluir JS con Estructura basica de paneles
$slCss= ".x-grid-group {                                                // OPCIONAL:  Declarar una Regla CSS 
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;	
            font-size-adjust:none;
            font-style:normal;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }

        .x-grid-emptyrow {
            font-family:arial,tahoma,helvetica,sans-serif;
            font-size:11px;
            font-size-adjust:none;
            font-style:italic;
            font-variant:normal;
            font-weight:bold;
            line-height:13px;
            white-space:nowrap;
        }
    
        /* Example Styles... */
        .cell-align-right .x-grid-cell-text  {
            text-align: right;
        }
		.boton-menu{width:210px !important}
        .leftfloated {float: left} 
       
        #north_left {
            float:left;
            width:50%;
            height:70px;
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_emp'])? $_SESSION['logo_emp']: "logo_app.png" ) ." );
            background-repeat:no-repeat;
            text-align:left;
        }
        #north_right{
            float:right;
            width:50%;
            height:70px; 
            padding:0;
            margin:0;
            background-color:#ffffff;
            background-image:url(" . (isset($_SESSION['logo_mod'])? $_SESSION['logo_mod']: "header_app_caja.jpg" ) ." );
            background-repeat:no-repeat;
            text-align:right;
        }
        Body{
			align:center;
        }
        .iconImprimir{
            background-image:url(../Images/printer.png) !important;
        }
        .iconBuscar{
            background-image:url(../Images/find.png) !important;
        }
        .iconNuevo{
            background-image:url(../Images/page_white.png) !important;
        }
        .iconGrabar{
            background-image:url(../Images/disk.png) !important;
        }
        .iconBorrar{
            background-image:url(../Images/delete.png) !important;
        }
        .iconSalir{
            background-image:url(../Images/cancel.png) !important;
        }
	";
$goPanel->addCssRule($slCss);                                           // OPCIONAL Incluir la Regla declarada previamente, que se aplica a la pagina resultante
include("work_panels.qry.php");                                         // OPCIONAL Incluir archivo que define instrucciones SQL
/*
*       Definicion de variables de sesion que contiene las instrucciones SQl que se ejecutaran via ajax en el servidor
*       cuando se renderize el Front End
*/
$gsSesVar = "XXXX";                                                     //Prefijo de Id se variable de sesion.

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;        
//Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);

$modulo='LiEmTj';

if($_SESSION[$modulo]['ADD']==NULL)
				    $ADD= false;
else
				    $ADD=true;
if($_SESSION[$modulo]['UPD']==NULL)
				    $UPD= false;
			else
				    $UPD=true;
if($_SESSION[$modulo]['DEL']==NULL)
				    $DEL= false;
			else
				    $DEL=true;
				    
echo '<script languaje="JavaScript">
         var add="'.$ADD.'";
         var upd="'.$UPD.'";
         var del="'.$DEL.'";
      </script>';

$glExpanded = fGetParam('pExpan', 1);

$_SESSION[$gsSesVar. "_aplic"]= "";

$_SESSION["LiEmTj_empresa"] = "select emp_IDempresa cod,emp_Descripcion txt from seguridad.segempresas
                            where emp_Estado = 1 and emp_Grupo = '09'
                            and emp_Descripcion LIKE '%{query}%'
                            order by emp_Descripcion";

$_SESSION["LiEmTj_Paletizado"] =
	        "select par_Secuencia cod, concat(par_Secuencia,'/', par_Descripcion) txt
                    from genparametros where par_Clave = 'OGTCAR'
                    order by par_Secuencia";

$_SESSION["LiEmTj_embarlist"]=
"SELECT  emb_refoperativa as cod, concat(
                IF(emb_SemInicio = emb_SemTermino, emb_SemInicio, concat(emb_SemInicio, '-',emb_Semtermino )),
                '-', buq_Descripcion, '-', emb_numviaje, ' / ', v1.vau_descripcion, ' / ', v2.vau_descripcion
                 ) AS txt, emb_SemInicio semana
FROM 	liqembarques
	JOIN liqbuques on buq_codbuque = emb_codvapor
        join v_auxgeneral v1 on v1.vau_codauxiliar=emb_CodProducto
	join v_auxgeneral v2 on v2.vau_codauxiliar=emb_Consignatario
WHERE	emb_estado BETWEEN 0 and 40 and
        concat(emb_SemInicio,'-', buq_Descripcion, '-', emb_numviaje) LIKE '%{query}%' 
        /*AND
	{pSeman} between emb_SemInicio and emb_SemTermino*/
order by emb_SemInicio desc,buq_Descripcion asc";/* AND
  concat(buq_Descripcion, '-', emb_numviaje) LIKE '%{query}%'";*/

$_SESSION["LiEmTj_ptoEmbarque"] = "select pue_CodPuerto cod, pue_Descripcion txt from genpuertos
                                    where pue_CodPais=593 order by 2";

$_SESSION["LiEmTj_productores"] = "SELECT per_CodAuxiliar as cod, concat(per_Apellidos, ' ', per_Nombres) AS txt/*, 
        if(cat_activo = 1,'ACTIVO', 'INACT.') as Estado, par_Descripcion as Categoria ,par.par_Secuencia*/
        FROM (conpersonas per LEFT JOIN concategorias cat  ON per.per_CodAuxiliar = cat.cat_CodAuxiliar) 
        LEFT JOIN genparametros par ON par_clave = 'CAUTI' and cat.cat_Categoria = par.par_Secuencia
        where par.par_Secuencia IN (51,52) and concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%' 
        order by 2";

$_SESSION["LiEmTj_grupo"] = "SELECT per_CodAuxiliar as cod, concat(per_Apellidos, ' ', per_Nombres) AS txt/*, 
        if(cat_activo = 1,'ACTIVO', 'INACT.') as Estado, par_Descripcion as Categoria ,par.par_Secuencia*/
        FROM (conpersonas per LEFT JOIN concategorias cat  ON per.per_CodAuxiliar = cat.cat_CodAuxiliar) 
        LEFT JOIN genparametros par ON par_clave = 'CAUTI' and cat.cat_Categoria = par.par_Secuencia
        where par.par_Secuencia = 52 and concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%' 
        order by 2";

$_SESSION["LiEmTj_contenedor"] =
		"select cnt_Serial as cod,cnt_Serial as txt from opecontenedores 
                    where cnt_Serial like '%{query}%'
                    order by cnt_Serial" ;
        
$_SESSION["LiEmTj_estado"] = "select par_Secuencia cod,par_Descripcion txt from genparametros where 
        par_clave in ('LGESTA') order by 1";
        
$_SESSION["LiEmTj_zona"] = "select par_Secuencia cod,par_Descripcion txt from genparametros 
        where  par_Clave = 'LSZON' and par_Descripcion LIKE '%{query}%'  order by 2";

$goPanel->render();
ob_end_flush();
?>

