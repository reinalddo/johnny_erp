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
$goPanel->title = $_SESSION["g_empr"] . " - Reporte Cliente";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/ext3/ux/Ext.ux.app.galeria");




$loadJSFile='../LiCiRp_ReporteCargaPnl';

$db->debug = fGetParam('pAdoDbg', false);

$gsSesVar = "LiCiReportes";
 

$_SESSION["LiCiRp_Cliente"] = "select   per_CodAuxiliar cod
                                            ,concat(per_CodAuxiliar,' ',per_apellidos,' ',per_nombres) txt
                                    from conpersonas per
                                    inner JOIN concategorias cat on
                                             cat.cat_CodAuxiliar = per.per_CodAuxiliar
                                             and cat.cat_Categoria = 50
                                    WHERE (CONCAT(UPPER(per_CodAuxiliar),' ',UPPER(per_apellidos),' ',UPPER(per_nombres))) LIKE UPPER('%{query}%')
                                    order by per_apellidos,per_nombres";

$_SESSION["LiCiRp_Vapor"] = "SELECT buq_codBuque as cod, buq_Descripcion as txt
                                    FROM liqbuques
                                    WHERE UPPER(buq_Descripcion) LIKE UPPER('%{query}%')
                                    order by buq_Descripcion ";                                    
                                    

$_SESSION["LiCiRp_Embarques"] = " SELECT emb_RefOperativa AS cod
                                    ,CONCAT('Cod:',emb_NumViaje
                                            ,CASE
                                                    WHEN emb_SemInicio != emb_SemTermino THEN CONCAT(' Semanas:',emb_SemInicio,'-',emb_SemTermino)
                                                    ELSE CONCAT(' Semana:',emb_SemInicio)
                                             END
                                            ,' Vapor:',e.emb_CodVapor,'-',b.buq_Descripcion,' Origen:',pdest.pue_Descripcion,' Destino:',pembc.pue_Descripcion) AS txt
                                  FROM liqembarques e
                                  LEFT JOIN liqbuques b ON b.buq_CodBuque = e.emb_CodVapor
                                  LEFT JOIN conpersonas cli ON cli.per_CodAuxiliar = emb_Consignatario
                                  LEFT JOIN genpuertos pdest ON pdest.pue_CodPuerto = e.emb_Destino
                                  LEFT JOIN genpuertos pembc ON pembc.pue_CodPuerto = e.emb_CodPuerto
                                  JOIN conperiodos ON per_Semana = emb_SemTermino AND per_Aplicacion = 'LI' AND per_Estado = 99 /*Estado cerrado*/
                                  WHERE CONCAT('Cod:',emb_NumViaje,' Vapor:',e.emb_CodVapor,'-',upper(b.buq_Descripcion),' Origen:',upper(pdest.pue_Descripcion),' Destino:',upper(pembc.pue_Descripcion)) LIKE UPPER('%{query}%')
                                  order by 1 desc ";


$_SESSION["LiCiRp_TarjasConsulta"] = "SELECT tar_NumTarja, tac_Semana, tac_Fecha, tac_Embarcador,
                                      CONCAT(per_Apellidos,' ',per_Nombres) AS Embarcador, 
                                      tac_PromCalibracion , tac_PromDedos, tac_PromPeso 
                                      FROM liqtarjacabec
                                      JOIN conpersonas ON per_CodAuxiliar = tac_Embarcador
                                      WHERE tac_Semana = IF({ptac_Semana}= 0,tac_Semana, {ptac_Semana}) /* UPPER('%{query}%') */
                                      and tar_NumTarja = IF({ptar_NumTarja} = 0,tar_NumTarja, {ptar_NumTarja}) /* UPPER('%{query}%') */
                                      ORDER BY tac_Semana, tar_NumTarja ";     

$_SESSION["LiCiRp_TxtSemanaDesde"] = "SELECT DISTINCT ltr_semanaDesde as cod , ltr_semanaDesde as txt
                                      FROM liqTextoReporte WHERE ltr_tipo = 'CALIDAD' and 
                                      ltr_CodCliente = 0 /*predeterminado*/ AND ltr_semanaDesde like '%{query}%' order by txt desc";
                                      
$_SESSION["LiCiRp_Textos"] = "select    ltr_registro,
                                        ltr_tipo, 
                                        ltr_semanaDesde, 
                                        ltr_CodCliente, 
                                        ltr_txt1Titulo, 
                                        ltr_txt1Desc, 
                                        ltr_txt2Titulo, 
                                        ltr_txt2Desc, 
                                        ltr_txt3Titulo, 
                                        ltr_txt3Desc, 
                                        ltr_visible,
                                        CASE  ltr_visible
						WHEN 1 THEN 'Si'
						ELSE 'No'
					END AS txVisible,
                                        ltr_fechaRegistro, 
                                        ltr_usuario
                                         
                                        FROM 
                                        liqTextoReporte  WHERE ltr_tipo = 'CALIDAD' and 
                                        ltr_CodCliente = {pltr_CodCliente} /*predeterminado*/ AND ltr_semanaDesde = {pltr_semanaDesde}
                                        order by ltr_semanaDesde";
                                        
$_SESSION["LiCiRp_TxtVisible"] = "SELECT 1 as cod, 'Si' as txt
                                  union
                                  SELECT -1 as cod, 'No' as txt ";
                                  
$_SESSION["LiCiRp_TextosInsertar"] = "select    0 as ltr_registro,
                                        ltr_tipo, 
                                        ' ' as ltr_semanaDesde, 
                                        ltr_CodCliente, 
                                        ltr_txt1Titulo, 
                                        ltr_txt1Desc, 
                                        ltr_txt2Titulo, 
                                        ltr_txt2Desc, 
                                        ltr_txt3Titulo, 
                                        ltr_txt3Desc, 
                                        1 as ltr_visible,
                                        'Si' as txVisible,
                                        sysdate() as ltr_fechaRegistro, 
                                        '".$_SESSION["g_user"]."' as ltr_usuario
                                         
                                        FROM 
                                        liqTextoReporte
                                        WHERE ltr_tipo = 'CALIDAD' AND ltr_CodCliente = {pltr_CodCliente} 
                                        AND ltr_semanaDesde =	(
                                                                SELECT MAX(ltr_semanaDesde) FROM liqTextoReporte WHERE ltr_tipo = 'CALIDAD'
                                                                AND ltr_CodCliente = {pltr_CodCliente} AND ltr_visible = 1
                                                                )
                                        ";
                                      

$goPanel->render();
?>