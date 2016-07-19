<?php
/** File MisLib.php:  Funciones Miscelaneas
*   @ Rev:   Feb 18/04
**/

/**
 * Aplica Busqueda:
 *
 * Recibe varias cookies de la pagina solicitante, las interpreta, generando la consulta
 * que recupera los datos, el array de los campos origen en el resultado de la consulta,
 * el array de campos destino (a los que debe exportar los datos). Si esta consulta genera un
 * solo registro de salida, exporta esos datos inmediatamente, caso contrario, abre una ventana
 * de busqueda de la cual puede elegir el usuario el registro que necesita.
 *
 **/

		global $Tpl;
		if (isset ($_COOKIE["coOrigen"]))  {
			if (isset ($_COOKIE["coOrigen"])) 	$coOri = explode(",", $_COOKIE["coOrigen"]);
			if (isset ($_COOKIE["coDestino"])) 	$coDes = explode(",", $_COOKIE["coDestino"]);
			if (isset ($_COOKIE["coCompon"])) 	$coCom = $_COOKIE["coCompon"];
			if (isset ($_COOKIE["coFocus"])) 	$coFoc = $_COOKIE["coFocus"];
			$paSuf = CCGetParam("pSufijo");     }
		else {
			 fMensaje ("NO SE PUDO EJECUTAR LA CONSULTA DE BUSQUEDA" );
//	  		 echo "NO SE PUDO EJECUTAR LA CONSULTA DE BUSQUEDA" ;
		}
	 	$sFun = "";
		for ( $i=0; $i<=count($coOri)-1; $i++) {  // Armar la funcion js que carga datos en pag de trabajo
		 	if ($i >0) $sFun  .= "\t";
			$sFun  .= "window.opener.top." . $coCom . "." . $coDes[$i] . $paSuf . ".value = document.Item_Lista." . $coOri[$i] . "[i].value; \n";
		}

		$sFun  .= "window.opener.top." . $coCom . "." . $coFoc . $paSuf . ".focus(); \n";   // Fijar focus en campo deseado
		$sFun .= "window.close()";
		$Tpl->SetVar("tvElegir",$sFun);
?> 
