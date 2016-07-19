<?php
   /**
     * File ConEst.php  Funciones para manejar estructuras de cuentas, tipo arbol.
     *   @Rev:   Feb 18/04
     *   @Rev:   fah 29/07/2009  Correcciones para que funcione con tablas virtuales(vista que apunta a otra DB), que tienen el problema de
     *   			 que los nombres de los campos devueltos estan siempre en minuscula y hay que aplicar un alias en la instruccion SQL
     *   			 para asegurar la referencia correcta al dato que retorna como arreglo asociativo.
    **/

   /**
     * Genera el arbol ascencente de las cuentas asociadas a una cuenta específica, ilcta.
     * Para una correcta generacion, procesar primero como tipo 'r'
     * (para que en las cuentas que no tengan posicion definida, primero grabe  la posicion correcta de la cuenta)
     * y luego como 'R' (para que el arbol ascendente de la cuenta tenga los numeros correctos de elemento
     * en la red, que se basan en la posicion)
     *
     * @access public
     * @param  $ilBase: ID de cuenta inicial en anlisis (tronco inicial)
     * @param  $ilcta entero cuenta a estudiar
     * @return nivel de la cuenta
     * Definir como global vars :  $ignivel , $p_mode, $igCuenta, $igPosic;
     */

	function fAscendentes($ilBase, $ilcta, $dbg ) {
    	global $ignivel , $p_mode, $igCuenta, $igPosic;
    	if ($ilcta > 1 ) {
            ++$ignivel;
	        $query = "SELECT cue_id as cue_ID, cue_padre, cue_codcuenta FROM concuentas WHERE cue_ID = " . $ilcta;
	        $result = mysql_query ($query)                  or die ("Consulta Fallida");
	        $line = mysql_fetch_array($result);
			$sltxt = $line['cue_codcuenta'];
            
	        if ($dbg) {
	        	print "\t<tr>  <td  bordersize='1'> </td> <td class='CobaltDataTD' bordersize='1' colspan='2' width='100%'> $sltxt       <td> ($ignivel)</td> </td>";
		        print "   </tr>\n";
	           }
	        switch ($p_mode ) {
                case "R":
                	--$igPosic;
                    fGrabaRed ($ilBase, $ilcta, $igPosic, $line["cue_codcuenta"], $line["cue_ID"], $dbg);
    	             break;
    	        default:
    	          break;
	           }
	        fAscendentes($ilBase, $line['cue_padre'], $dbg);
            }
    }
/**
 * Graba un registro en tabla de estructura de cuentas
 * @access public
 * @param  $ilBase: ID de cuenta inicial en anlisis (tronco inicial)
 * @param  $ilCuenta: Codigo de cuenta en anlisis
 * @param  $ilPosic: Posicion en la estructura de la cuenta
 * @param  $ilCodig: Còdigo de cuenta bajo analisis
 * @param  $ilPadre: Id de la cuenta que ocupa ilPosic dentro de la ascendencia de ilCuenta
 * @return void
 */
 function fGrabaRed($ilBase, $ilCuenta, $ilPosic, $ilCodig, $ilPadre, $dbg) {
        $query = "INSERT INTO conredcuentas VALUES(" . $ilBase . ", " . $ilPosic . ", '" . $ilCodig . "', " . $ilPadre . ") ";
	    if ($dbg) echo "<br>" . $query;	
        $result = mysql_query ($query)                       or die ("--Grabacion de red Fallida ");

    }

 /**
 * function fGeneraEstructura()
 * Analiza las cuentas ascendentes de una cuenta
 *
 * @access public
 * @param  $ilCuenta: 	Codigo de cuenta en anlisis (-1 si se trata de todas)
 * @param  $pmode:		Mdo de ejecuciòn: (d) Debug   (R) Reconstruccion total
 *					   					  (r) Reconstruccion de una cuenta
 * @param  $ilCodig: 	Còdigo de cuenta bajo analisis
 * @return void
 */
 function fGeneraEstructura($ilCuenta = -1, $p_mode = "r", $dbg = false) {
    	global $ignivel , $p_mode, $igCuenta, $igPosic, $igCodig;
	$link = mysql_connect(DBSRVR, DBUSER, DBPASS)  or die ("--- No puede Conectarse al Servidor de Base de datos");
	mysql_select_db (DBNAME)                         or die ("--- NO puede seleccionar la base de datos " . DBNAME);
	$query = "SELECT cue_ID AS cue_ID, cue_padre as cue_padre, cue_codcuenta as cue_codcuenta, cue_posicion as cue_posicion " .
		    "FROM concuentas ";
        if (isset($ilCuenta ) && $ilCuenta <> -1) $query .= " WHERE cue_ID = " . $ilCuenta;
        $query .= " Order by 3 ";
	echo "<br>" .$query;
        $rst = mysql_query ($query)                       or die ("Consulta Fallida");

	    if ($dbg) {
	        print "<table class='CobaltFormTable'> \n";
	    }
	    while ($line = mysql_fetch_array($rst)) {
//echo"<br>";print_r($line);		
	      $sltxt = $line['cue_codcuenta'];
	      $igCuenta = $line['cue_ID'];
	      if ($dbg) {
	          print "\t<tr>  <td class='CobaltDataTD' bordersize='1'> ";
	          print $line['cue_codcuenta'];
	          print "  </td>   </tr> " ;
	      }
	      $ignivel = 1;
	      $igPosic = $line['cue_posicion'];
	      $igCodig = $line['cue_codcuenta'];

	      switch ($p_mode ) {
	        case "R":       // Reconstruye la estructura de todas las cuentas
	            $query = "DELETE FROM conredcuentas WHERE red_cueid = " .   (is_null($igCuenta) ? "-100":$igCuenta);
		    echo "<br>" .$query;
	            $result = mysql_query ($query)                       or die ("Eliminacion de red anterior Fallida");
	            if ($dbg) {        // Debug mode
	               print "\t<tr> <td></td> <td>  Nivel-R: " . $ignivel .  "  </td>  </tr> " ;
	               }	
	            fGrabaRed($line['cue_ID'], $line['cue_ID'], $igPosic, $line["cue_codcuenta"], $line['cue_ID'], $dbg);
	            fAscendentes($line['cue_ID'], $line['cue_padre'], $dbg);
	            break;
	        case "r":       // reconstruye la estructura de una cuenta
	            if ($dbg) {        // Debug mode
	               print "\t<tr> <td></td> <td>  Nivel-r: " . $ignivel .  "  </td>  </tr> " ;
	               }
   	            fAscendentes($line['cue_ID'], $line['cue_padre'], $dbg);
	             break;
	        default:
	          break;
	      }
          $query = "UPDATE concuentas SET cue_posicion = " . $ignivel . " WHERE cue_id = " . $line['cue_ID'];
          $result = mysql_query ($query)                       or die ("Consulta Fallida nivel 1");
	    }
	    if ($dbg) {
	        print "<table>";
	    }
    }
?>
