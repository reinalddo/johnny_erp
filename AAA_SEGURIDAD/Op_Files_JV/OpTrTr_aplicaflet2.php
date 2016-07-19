<?php
/*
*	Aplicar precios a un embarque, segun los datos de ingreso
*	@author  Fausto Astudillo
*	@package Operaciones
*	@rev     fah22/03/08    Aplicar concepto de que los precios oficiales se tienen vigencia hasta que haya,
*	                        uno nuevo sin que sea necesario definirlos semana a semana si no hay variacion.
*/
ob_start();
if (!isset ($_SESSION)) session_start();
include('../LibPhp/queryToJson.class.php');
class aplicaprec  extends  clsQueryToJson {
    function init(){
        $this->type="U";
    }
    function beforePrepareSql(){
        global $db;
        $db->debug=fgetParam("pAdoDbg",0);
       $dlPrecio = fGetParam('pNvoPr', false); //Nuevo Precio
       $ilSeman  = fGetParam('pSeman', false);
       $ilEmbarq = fGetParam('pRope',  false);
       $ilTarja  = fGetParam('pTarj',  false);
       $ilProduc = fGetParam('pProd',  false); 
       $ilZona   = fGetParam('pZona',  false);
       $ilMarca  = fGetParam('pMarc',  false);
       $ilEmpaq  = fGetParam('pEmpq',  false);
       $dlFecha  = fGetParam('pFech',  false);
       $dlPreAn  = fGetParam('pPrAn',  false); // Precio Anterior
       $ilItem   = fGetParam('pPrdc',  40002); // Default: Banano de Segunda (MS)
       $ilFlete  = fGetParam('pFlete',  0);
       $slConCond= "";
       if ($ilSeman)  $slConCond .= " tac_Semana = " . $ilSeman;
       if ($ilEmbarq >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tac_RefOperativa = " . $ilEmbarq;
       if ($ilTarja  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tar_NumTarja = " . $ilTarja;
       if ($ilProduc >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tac_Embarcador = " . $ilProduc;
       if ($ilZona   >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tac_Zona = " . $ilZona;
       if ($ilMarca  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tad_CodMarca = " . $ilMarca;
       if ($ilEmpaq  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tad_CodCaja = " . $ilEmpaq;
       if (strlen($ilFecha)  >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tac_Fecha = '" . $ilEmpaq . "' ";
       //if ($dlPreAn <>0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "(tad_ValUnitario - tad_DifUnitario) = " . $dlPreAn;
       if ($ilItem   >0) $slConCond .= ((strlen($slConCond) >1)? " AND ": "") . "tad_CodProducto = " . $ilItem;
       $slQry = $this->arrayQry[count($this->arrayQry)-1]; // Tomar la ultima instruccion Sql
       $slQry = str_replace("{pCond}", $slConCond, $slQry);
       $slQry = str_replace("{pFlete}", $ilFlete, $slQry);
       $this->arrayQry[count($this->arrayQry)-1] = $slQry; // reasignar la ultima SQL
    }
}
;
$data = new aplicaprec();
$data->getJson();
ob_end_flush();
?>
