<?php
/** File valiLib.php:  Funciones de Validacion
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
function fAplicaBusqueda() {
	global $Tpl;

	$SearchField = CCGetParam("SearchField","");

    if ($SearchField == " ") CallCustomerSearch();

	$db = new clsDBdatos;
    $slSufijo = "";
	if (isset ($_COOKIE["coSelect"]))  {
		$coSql = $_COOKIE["coSelect"] ;
		if (isset ($_COOKIE["coWhere"])) 	$coWhe = $_COOKIE["coWhere"];
		if (isset ($_COOKIE["coOrigen"])) 	$coOri = explode(",", $_COOKIE["coOrigen"]);
		if (isset ($_COOKIE["coDestino"])) 	$coDes = explode(",", $_COOKIE["coDestino"]);
		if (isset ($_COOKIE["coCompon"])) 	$coCom = $_COOKIE["coCompon"];
		if (isset ($_COOKIE["coSufijo"])) 	$slSufijo = $_COOKIE["coSufijo"];
		if (isset ($_COOKIE["coFocus"])) 	$coFoc = $_COOKIE["coFocus"];  }

	else {
		 include_once (RelativePath . "/LibPhp/SegLib.php") ;
		 fMensaje (" FALTAN PARAMETROS PARA EJECUTAR VALIDACION" );
		 }
//echo "SUFIJO: " . $slSufijo;
	$sSQL = stripslashes($coSql . " " . $coWhe);
//echo "sql= " .	$coSql .  "<BR>" .  	$coWhe . "<BR>";
//echo "Ori= " . count($coOri) ." elem : " .	$coOri[0] . " / " . $coOri[1] . " / " .$coOri[2] . " / " .$coOri[3] . " / " .$coOri[4] . " / " . "<BR>";
//echo "Des= " .	$coDes . "<BR>";
//echo "------------------------------" . "<BR>";
	if(!$db->query($sSQL)) {
		 include_once (RelativePath . "/LibPhp/SegLib.php") ;
		 fMensaje (" NO SE PUDO EJECUTAR LA CONSULTA DE BUSQUEDA:  <br> ". $sSQL );
	   }
	$TotRecs = $db->num_rows();
//echo "Coincidencias: " . $TotRecs . "<br>";
	if ($TotRecs == 1)    {
	    $next_record = $db->next_record();
		$sPar  = "LoadTopFrame(";
		$sFun = "";
		$sFun2 = "<script language='Javascript'>\n";
	    $sFun2 .= "function ProcessItem() {\n";
		$elem = count($coOri);

	// Armar la funcion js que carga datos en pag de trabajo
 		for ( $i=0; $i < $elem ; $i++ ) {
		 	if ($i > 0) { $sPar .= ", " ;}
			$sPar  .= " dat". $i ;
			$sFun  .= "parent.document." . $coCom . "." . $coDes[$i] . $slSufijo . ".value = dat" . $i . "; \n";
 //			$sFun  .= "alert('Parent:' + parent.name); \n" ;
			$sFun2 .= "var dat" . $i. " = '" . $db->f($coOri[$i]) . "';\n";
		}
//      $sFun2 .= "alert('" . $sFun2 . "');";
 		$sFun  .= "parent.document." . $coCom . "." . $coFoc . $slSufijo . ".focus(); \n";   // Fijar focus en campo deseado
 	 	$sFun  .= "parent.document." . $coCom . "." . $coFoc . $slSufijo . ".select(); \n";   // Fijar focus en campo deseado	
 	 	$sFun  .= "location = 'about:blank'";    // Borrar el contenido de la pagina
		$sFun2 .= $sPar . "); }\n";
		$sFun2 .= "</script>\n";
		$WindowBehavior =  "function " . $sPar . ") { \n ". $sFun . " }\n";
	    $WindowBehavior .= "</script>\n";
echo "<br> wB  :-------------------------<br>" . $WindowBehavior;
echo "<br> f   :-------------------------<br>" . $sFun ;
echo "<br> f2  :-------------------------<br>" . $sFun2 ;
echo "<br> par :-------------------------<br>" . $WindowBehavior . "<br>" ;
		$Tpl->SetVar("WindowBehavior","ProcessItem()");
	    $Tpl->SetVar("funLoadTopFrame", $WindowBehavior);
	    $Tpl->SetVar("WindowBehavior_1", $sFun2);
		}
	else {
		include_once (RelativePath . "/LibPhp/SegLib.php");
	    CallCustomerSearch();
	}
 	unset ($db);
 }

 /**
 * GeneraJS:
 * Basado en los datos contenidos en cookies, genera un Jscript que pasa el
 * contenido de un fila del recordset resultante de la busqueda cuando el
 * usuario hace click en un control. Se ejecuta solo si Opcode = R (Retornar Valor)
 **/
 
 function fGeneraJs($pFormdat) {
 	global $Tpl;
 	$sFun = "";
 	$paSuf ="";
 	if (CCGetParam("pOpCode", "E") == "R") {
    	if (isset ($_COOKIE["coOrigen"]))  {
    		if (isset ($_COOKIE["coOrigen"])) 	$coOri = explode(",", $_COOKIE["coOrigen"]);
    		if (isset ($_COOKIE["coDestino"])) 	$coDes = explode(",", $_COOKIE["coDestino"]);
    		if (isset ($_COOKIE["coCompon"])) 	$coCom = $_COOKIE["coCompon"];
    		if (isset ($_COOKIE["coFocus"])) 	$coFoc = $_COOKIE["coFocus"];
    		if (isset ($_COOKIE["coSufijo"])) 	$paSuf = $_COOKIE["coSufijo"];
    		 }
     //      echo $paSuf;
    	else {
      		 echo "NO SE PUDO EJECUTAR LA CONSULTA DE BUSQUEDA." ;
    	}
    	$sFun1="";
    	$sFun2="";
     	for ( $i=0; $i<=count($coOri)-1; $i++) {  // Armar la funcion js que carga datos en pag de trabajo
    	 	if ($i >0) {
                 $sFun2  .= "\t";
    	 	     $sFun1  .= "\t";
    	 	    }
        if (strlen($coCom) > 1 ){    	 	
    		$sFun2  .= "window.opener.parent.document." . $coCom . "." . $coDes[$i] . $paSuf . ".value = document." . $pFormdat . "." . $coOri[$i] . "[i].value; \n";
    		$sFun1  .= "window.opener.parent.document." . $coCom . "." . $coDes[$i] . $paSuf . ".value = document." . $pFormdat . "." . $coOri[$i] . "_1.value; \n";
    		}
    	}
        $sFun  = "if(document." . $pFormdat . ".num_Recs.value > 1) { \n";
        $sFun  .= $sFun2 . "} \n else {";
        $sFun  .= $sFun1 . "} \n";
        if (strlen($coCom) > 1 ){
        	$sFun  .= "window.opener.parent.document." . $coCom . "." . $coFoc . $paSuf . ".focus(); \n";   // Fijar focus en campo deseado
           	$sFun  .= "window.opener.parent.document." . $coCom . "." . $coFoc . $paSuf . ".select(); \n";   // Fijar focus en campo deseado	
           	$sFun  .= "window.opener.location='about:blank' \n";
            $sFun .= "window.close()";
        }
        }	
    $Tpl->SetVar("tvElegir",$sFun);
}
?>
