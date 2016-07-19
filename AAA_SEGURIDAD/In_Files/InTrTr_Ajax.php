<?php
/*
 *      @rev    fah 12/02/09    Presentar correctamente el nombre de bodega en transacciones de inventario,
 *                              el nombre de campo de ta ltabla es com_emisor en lugar de com_Emisor, se aplica un alias
 *                              en la instruccion sql.
 *      @rev    fah 10/06/09    Aplicar restricciones de acceso a comprobantes, segun perfil /usuario en $_SESSION[restr]
 *      @rev    fah 10/09/09    Aplicar Restriccion de Duracin de comprobantes
 *      @rev    fah 10/09/09    ACorregis bug en manejo de Restricciones de TipoComprobante (el modulo se asumi CO, debido al paramtero pMod/pModul)
 **/
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
include("GenUti.inc.php");
  

$DBdatos = new clsDBdatos();
        $pTipoComp = CCGetRequestParam("pTipoComprobante", ccsGet);
        $pEmisor = CCGetRequestParam("pEmisor", ccsGet);
        $pItem = CCGetRequestParam("pItem", ccsGet);
        
        $tipoComprobante = CCGetDBValue("SELECT * FROM `genclasetran` WHERE cla_aplicacion = 'IN' AND cla_ClaTransaccion = '-1' AND cla_tipoComp = '".$pTipoComp."'", $DBdatos);
        
        if($tipoComprobante==""){
           $data = array("valor" => "3", "total" => "");
           echo json_encode($data);
           $DBdatos->close();
           exit();
        }
        
        
$parValor = CCGetDBValue("SELECT par_Valor1 FROM genparametros WHERE par_ClaVE  = 'EGINV'" , $DBdatos);
if($parValor==""){
    $parValor = "0";
}
        
$parTotal = CCGetDBValue("SELECT IFNULL(SUM(det_CantEquivale*pro_signo),0) AS act_stock, act_codauxiliar AS act_codauxiliar_stock  FROM invprocesos JOIN
concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem 
            	WHERE com_emisor = '" . $pEmisor . "' AND act_codauxiliar = '" .$pItem. "' 
            	GROUP BY act_codauxiliar" , $DBdatos);
if($parTotal==""){
    $parTotal = "0";
}

$data = array("valor" => $parValor, "total" => $parTotal);
$DBdatos->close();
echo json_encode($data);

?>
