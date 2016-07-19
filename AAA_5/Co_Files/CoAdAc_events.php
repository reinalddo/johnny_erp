<?php
/**
*    Eventos para CoAdAc.
*    Generado por CCS
*    @package	 eContab
*    @subpackage Administracion
*    @program    CoAdAc
*    @author     fausto Astudillo H.
*    @version    1.0 01/Dic/05
*/
//BindEvents Method @1-FA3AC75D
/**
*   Bandeja de eventos definidos para el servidor
*/
function BindEvents()
{
    global $CCSEvents;
    $CCSEvents["OnInitializeView"] = "Page_OnInitializeView";
}
//End BindEvents Method

//Page_OnInitializeView @1-493DD3AA
/**
*   Al inicializar la pagina
*/
function Page_OnInitializeView()
{
    $Page_OnInitializeView = true;
//End Page_OnInitializeView

//Custom Code @363-C392F04C
// -------------------------  ESTO SE AGREGO
    global $CoAdAc;

// -------------------------
//End Custom Code

//Close Page_OnInitializeView @1-81DF8332
    return $Page_OnInitializeView;
}
//End Close Page_OnInitializeView
?>
