<?
/*
*   Panel general de Operaciones
*   @created    Oct/30/07
*   @author     fah
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
$goPanel->title = $_SESSION["g_empr"] . " - CAJA ";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../css/multiselect");
$goPanel->addCssFile("column-tree");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");                 // Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extFormExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.ms.MultiSelect");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("DDView");
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

            background-repeat:no-repeat;
            text-align:right;
        }
        Body{
			align:center;
        }
        .textLeft{
            text-align: left;
        }
        .x-btn-izq .x-btn-center { text-align: left;}
        
        .x-btn-izq .x-btn-over .x-btn-left,.x-btn-izq .x-btn-focus .x-btn-left{background-position:0 -108px;}
        .x-btn-izq .x-btn-over .x-btn-right,.x-btn-izq .x-btn-focus .x-btn-right{background-position:0 -144px;}
        .x-btn-izq .x-btn-over .x-btn-center,.x-btn-izq .x-btn-focus .x-btn-center{background-position:0 -180px;}
        
        .iconAnular{
            background-image:url(../Images/cross.png) !important;
        }
        .iconImprimir{
            background-image:url(../Images/printer.png) !important;
        }
        .iconConsultar{
            background-image:url(../Images/book_open.png) !important;
        }
        .iconSalir{
            background-image:url(../Images/cancel.png) !important;
        }
        .iconBuscar{
            background-image:url(../Images/find.png) !important;
        }
        
        .yellow-row{ background-color: #FBF8BF !important; } 

	";
$goPanel->addCssRule($slCss);                                           // OPCIONAL Incluir la Regla declarada previamente, que se aplica a la pagina resultante
include("work_panels.qry.php");                                         // OPCIONAL Incluir archivo que define instrucciones SQL
/*
*       DEfinicion de variables de sesion que contiene las instrucciones SQl que se ejecutaran via ajax en el servidor
*       cuando se renderize el Front End
*/
$gsSesVar = "XXXX";                                                     //Prefijo de Id se variable de sesion.

$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);

$glExpanded = fGetParam('pExpan', 1);

$_SESSION["CoTrTr_bancos"] = "SELECT per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt 
                            FROM conpersonas JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
                             WHERE cat_categoria = 10
                            and concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%'
                            order by per_Apellidos,per_Nombres";
                            
$_SESSION["CoTrTr_cuentas"] = "SELECT det_codcuenta AS cod, /*concat(det_Codcuenta, ' ', cue_Descripcion)*/
            replace(replace(replace(replace(replace(replace(concat(cue_Descripcion, ' - ', det_Codcuenta),'Ñ','N'),'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') as txt
            FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
            JOIN concuentas on cue_codcuenta = det_codcuenta 
            JOIN genparametros on par_clave = 'CUCX{pTipo}'
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11 
            WHERE det_codcuenta like concat(par_valor1 , '%') 
            GROUP BY 1,2
            ORDER BY 2" ;
                            
$_SESSION["CoTrTr_lista_1"] = "SELECT distinct 1 txt_tipo
                                ,case when par_Clave = 'CUCXC' then 'C' else 'P' end tipo2
                                ,case when par_Clave = 'CUCXC' then 'Cobrar' else 'Pagar' end tipo,
                                'task-folder' as iconCls, 
                                'master-task' as cls, 'col' as uiProvider,
                                '' AS codcuenta, 
                                '' as nombrcue
                            FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
                            JOIN concuentas on cue_codcuenta = det_codcuenta 
                            JOIN genparametros on par_clave in ('CUCXP','CUCXC' )
                            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11 
                            WHERE det_codcuenta like concat(par_valor1 , '%') 
                            GROUP BY 1,2
                            ORDER BY det_codcuenta";
                            
$_SESSION["CoTrTr_lista_2_C"] = "SELECT 2 txt_tipo
                                ,case when par_Clave = 'CUCXC' then 'C' else 'P' end tipo2
                                ,case when par_Clave = 'CUCXC' then 'C' else 'P' end tipo,
                                'task-folder' as iconCls, 
                                'master-task' as cls, 'col' as uiProvider,
                                det_codcuenta AS codcuenta, 
                                replace(replace(replace(replace(replace(replace(cue_Descripcion/*concat(cue_Descripcion, ' - ', det_Codcuenta)*/,'Ñ','N'),'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') as nombrcue
                            FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
                            JOIN concuentas on cue_codcuenta = det_codcuenta 
                            JOIN genparametros on par_clave in ('CUCXC' )
                            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11 
                            WHERE det_codcuenta like concat(par_valor1 , '%') 
                            GROUP BY 1,2
                            ORDER BY det_codcuenta";
                            
$_SESSION["CoTrTr_lista_2_P"] = "SELECT 2 txt_tipo
                                ,case when par_Clave = 'CUCXC' then 'C' else 'P' end tipo2
                                ,case when par_Clave = 'CUCXC' then 'C' else 'P' end tipo,
                                'task-folder' as iconCls, 
                                'master-task' as cls, 'col' as uiProvider,
                                det_codcuenta AS codcuenta, 
                                replace(replace(replace(replace(replace(replace(cue_Descripcion/*concat(cue_Descripcion, ' - ', det_Codcuenta)*/,'Ñ','N'),'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') as nombrcue
                            FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
                            JOIN concuentas on cue_codcuenta = det_codcuenta 
                            JOIN genparametros on par_clave in ('CUCXP')
                            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11 
                            WHERE det_codcuenta like concat(par_valor1 , '%') 
                            GROUP BY 1,2
                            ORDER BY det_codcuenta";

//proveedores, acreedores
$_SESSION["CoTrTr_beneficiario"] = "SELECT per_CodAuxiliar as cod, concat(per_Apellidos, ' ', per_Nombres) AS txt/*, 
        if(cat_activo = 1,'ACTIVO', 'INACT.') as Estado, par_Descripcion as Categoria ,par.par_Secuencia*/
        FROM (conpersonas per LEFT JOIN concategorias cat  ON per.per_CodAuxiliar = cat.cat_CodAuxiliar) 
        LEFT JOIN genparametros par ON par_clave = 'CAUTI' and cat.cat_Categoria = par.par_Secuencia
        where par.par_Secuencia in (53, 62) and concat(per_Apellidos, ' ', per_Nombres) LIKE '%{query}%' 
        order by 2";
        
//Reimpresion de Comprobantes
//Tipos de Comprobante -Formulario de Cheque y Comprobante Contable.
$_SESSION["CoTrTr_TiposComp"] = "SELECT cla_TipoTransacc AS cod ,concat(upper(cla_TipoTransacc),'-',upper(cla_Descripcion)) AS txt, cla_Informe, IFNULL(cla_Cheque,'../Co_Files/CoTrTr_chequefza.php') cla_Cheque
                                FROM invprocesos  
                                JOIN genclasetran  ON cla_tipoComp = cla_TipoTransacc
                                WHERE pro_codProceso = 1001
                                AND cla_IndCheque = 1
                                and concat(upper(cla_TipoTransacc),'-',upper(cla_Descripcion)) LIKE '%{query}%' ";

$_SESSION[$gsSesVar. "_aplic"]= "";
$goPanel->render();
ob_end_flush();
?>

