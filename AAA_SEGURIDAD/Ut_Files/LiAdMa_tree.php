<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>MARCAS / COMPONENTES </title>
	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<?php
	session_start();
   	include ("libjs/layersmenu-browser_detection.js");
	include_once ("General.inc.php");      					// Definiciones generales
  	include ("lib/aaa_GenHtml.inc");
    include_once ("lib/PHPLIB.php");                         // Templates manager
    include_once ("lib/layersmenu-common.inc.php");          // Funciones Comunes de Arbol	
	include ("lib/treemenu.inc.php");
	
	//include ("../Common.php");
	//include ("../LibPhp/SegLib.php");
	
	if (!isset($_SESSION["g_empr"])) fErrorPage('','DEBE LOGEARSE CORRECTAMENTE ', true, false);
//	$DBseguridad= New clsDBseguridad;
	$slQuery = "";
	$mid = new TreeMenu();
	$mid->setDBConnParms(DATOS_CON);
	$mid->persistent=true;

	$db = DB::connect($mid->dsn, true);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//	$dbresult = $db->query("DELETE FROM tmpmarcas");
	$dbresult = $db->query("DROP TABLE IF EXISTS tmpmarcas");
	if (DB::isError($db)) {
	    $mid->error("NO SE ELIMINO LA TABLA TEMPORAL" . $db->getMessage());
	}
// echo "temporal" . "<BR>";
//	$slQrytmp  = "CREATE TABLE IF NOT EXISTS tmpmarcas(tmp_ID int, ";
	$slQrytmp  = "CREATE TEMPORARY TABLE IF NOT EXISTS tmpmarcas(tmp_ID int, ";
	$slQrytmp .=         "tmp_parentID int, tmp_descripcion varchar(50), ";
	$slQrytmp .=         "tmp_coment tinyblob, tmp_href varchar(100)) ";

	$dbresult = $db->query($slQrytmp);
	if (DB::isError($db)) {
	    $mid->error("NO SE CREO LA TABLA TEMPORAL" . $db->getMessage());
	}
//echo "<BR><BR>Insert NODO 1" . "<BR>";
//	$slQuery = "CREATE TABLE IF NOT EXISTS tmpmarcas " .

	$slQuery  = "INSERT INTO tmpmarcas " .
	            "VALUES (10 , 1, ' -PROPIAS', 'CLASE DE MARCA', '')";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("ERROR AL INSERTAR NODO 1" . $db->getMessage());
	}
//echo "Insert NODO 2" . "<BR>";	
	$slQuery  = "INSERT INTO tmpmarcas " .
	            "VALUES (20 , 1, '-DE TERCEROS', 'CLASE DE MARCA', '')";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("ERROR AL INSERTAR SEGMENTO 2" . $db->getMessage());
	}
	
//echo "Insert NODO 3" . "<BR>";	
	$slQuery  = "INSERT INTO tmpmarcas " .
	            "VALUES (30 , 1, '-N.D.', 'CLASE DE MARCA', '')";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("ERROR AL INSERTAR SEGMENTO 3" . $db->getMessage());
	}
//echo "Marcas Propias" . "<BR>";
	$slQuery   = "INSERT INTO tmpmarcas SELECT  par_Secuencia + (10000) as tmp_ID, " .
	                   " 10 as tmp_parentID, par_descripcion as tmp_descripcion, " .
	                   " '  MARCA  ' as tmp_coment, " .
	                   " concat('../Li_Files/LiAdMa_mant.php?par_Secuencia=' , par_Secuencia) as tmp_href " .
	                   " FROM genparametros  " .
	                   "WHERE par_Clave = 'IMARCA' AND par_Valor1 = '1' AND par_Valor3 = '1' ";
    $dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("ERROR AL INSERTAR SEGMENTO 4" . $db->getMessage());
	}
//echo "Marcas de Terceros <br>";
	$slQuery   = "INSERT INTO tmpmarcas SELECT  par_Secuencia + (10000) as tmp_ID, ".
	                   " 20 as tmp_parentID, par_descripcion as tmp_descripcion, " .
	                   " '  MARCA  ' as tmp_coment, ".
	                   " concat('../Li_Files/LiAdMa_mant.php?par_Secuencia=' , par_Secuencia) as tmp_href ".
	                   " FROM genparametros  ".
	                   "WHERE par_Clave = 'IMARCA' AND par_Valor1 = '2' AND par_Valor3 = '1' ";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo "No DEf. <br>";
	$slQuery   = "INSERT INTO tmpmarcas SELECT  par_Secuencia + (10000) as tmp_ID, ".
	                   " 30 as tmp_parentID, par_descripcion as tmp_descripcion, " .
	                   " '  MARCA  ' as tmp_coment, ".
	                   " concat('../Li_Files/LiAdMa_mant.php?par_Secuencia=' , par_Secuencia) as tmp_href ".
	                   " FROM genparametros  ".
	                   "WHERE par_Clave = 'IMARCA' AND par_Valor1 = '9999' AND par_Valor3 = '1' ";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}

//echo "1 ";
	$slQuery  = "INSERT INTO tmpmarcas " .
	            "SELECT (100000 + caj_CodCaja) as tmp_ID, (caj_CodMarca + 10000) as tmp_parentID, " .
                        "concat(caj_Abreviatura, ' - ', caj_Descripcion) as tmp_descripcion, " .
                        "'EMPAQUE'  as tmp_coment, " .
	                    "concat('../Li_Files/LiAdTc_mant.php?caj_CodCaja=',caj_CodCaja ) as tmp_href " .
	                "FROM   liqcajas " ;
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo "<BR><br>---" . $slQuery  . "<BR><br>";	
//    echo "2 ";
    $slQuery  = "INSERT INTO tmpmarcas " .
	            "SELECT (200000 + caj_CodCaja) as tmp_ID, ".
                        "(100000 + caj_CodCaja) as tmp_parentID, ".
                        "concat('1.- ', cte_Referencia, ' - ', cte_Descripcion) as tmp_Descripcion, ".
                        "'COMPONENTE CARTON'  as tmp_coment, " .
	                    "concat('../Li_Files/LiAdRe_mant.php?dos_CodComponente=', caj_Componente1, '&cte_Codigo=', cte_Codigo  ) as tmp_href " .
	                "FROM   liqcajas LEFT JOIN liqcomponent ON cte_Codigo = caj_Componente1 ".
                    "WHERE cte_clase = 1";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo ($slQuery)  . "<BR><br>";
//echo "3 ";

    $slQuery  = "INSERT INTO tmpmarcas " .
	            "SELECT (300000 + caj_CodCaja) as tmp_ID, ".
                        "(100000 + caj_CodCaja) as tmp_parentID, ".
                        "concat('2.- ',cte_Referencia, ' - ', cte_Descripcion) as tmp_descripcion, ".
                        "'COMPONENTE PLASTICO'  as tmp_coment, " .
	                    "concat('../Li_Files/LiAdRe_mant.php?dos_CodComponente=', caj_Componente2, '&cte_Codigo=', cte_Codigo  ) as tmp_href " .
	                "FROM   liqcajas LEFT JOIN liqcomponent ON cte_Codigo = caj_Componente2 ".
                    "WHERE cte_clase = 2";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo "4 <br><br><br> ";	
// echo ($slQuery)  . "<BR><br>";
	$slQuery  = "INSERT INTO tmpmarcas " .
                "SELECT (400000 + caj_CodCaja) as tmp_ID, ".
                        "(100000 + caj_CodCaja) as tmp_parentID, ".
                        "concat('3.- ',cte_Referencia, ' - ', cte_Descripcion), ".
                        "'COMPONENTE MATERIALES'  as tmp_coment, " .
	                    "concat('../Li_Files/LiAdRe_mant.php?dos_CodComponente=', caj_Componente3, '&cte_Codigo=', cte_Codigo  ) " .
	                "FROM   liqcajas LEFT JOIN liqcomponent ON cte_Codigo = caj_Componente3 ".
                    "WHERE cte_clase = 3";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo ($slQuery)  . "<BR><br>";
//echo "5 ";
    $slQuery  = "INSERT INTO tmpmarcas " .
	            "SELECT (500000 + caj_CodCaja) as tmp_ID, ".
                        "(100000 + caj_CodCaja) as tmp_parentID, ".
                        "concat('4.- ', cte_Referencia, ' - ', cte_Descripcion), ".
                        "'COMPONENTE ETIQUETA'  as tmp_coment, " .
	                    "concat('../Li_Files/LiAdRe_mant.php?dos_CodComponente=', caj_Componente4,'&cte_Codigo=', cte_Codigo  ) " .
	                "FROM   liqcajas LEFT JOIN liqcomponent ON cte_Codigo = caj_Componente4 ".
                    "WHERE cte_clase = 4";
	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
//echo ($slQuery)  . "<BR>";	
//echo "6 ";

	$dbresult = $db->query($slQuery);
	if (DB::isError($db)) {
	    $mid->error("CONEXION CON LA DASE DE DATOS" . $db->getMessage());
	}
    if (!dbresult) echo "ERRR";
    
    $slQuery = " select * from tmpmarcas";
    
	$mid->setTableName("tmpmarcas" );
	$mid->setTableFields(array(
	    "id"        => "tmp_ID",
	    "parent_id" => "tmp_parentID",
	    "text"      => "tmp_descripcion",
	    "href"      => "tmp_href",
	    "title"     => "tmp_coment",
	    "icon"      => "",
	    "target"    => "'myIframe1'",
	    "orderfield"    => "tmp_parentID",
	    "expanded"  => "0"));
	unset($db);
	$mid->scanTableForMenu("treemenu1");
	$mid->newTreeMenu("treemenu1");

?>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<link rel="stylesheet" href="./Themes/layerstreemenu.css" type="text/css"></link>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
</head>
<body bgcolor="#FFFFF7" style="background-color:#FFFFF7">
    <table >
        <tr height="10">
            <td>&nbsp;
            </td>
        </tr>
        <tr>
            <td valign="top">
                        	<div class="normalbox" >
                            	<div class="normalbox" align="left" height="400px" >
                        	        <font class="mediumtxt" style="FONT-SIZE:8">
                        	            <CENTER>MARCAS DE FRUTA DEFINIDAS:<BR>
                        	                    Presione sobre un Elemento para Ver / Editar su contenido
                        	            </CENTER>
                        	        </font>
                        	    </div>
                        	        <?php
                        	            $mid->printTreeMenu("treemenu1");
                        	            unset($mid);
                        	        ?>
                            </div>
            </td>
            <td valign="top">
                <iframe frameborder=0 name="myIframe1" width="700" height ="600" style="background-color:#FFFFF7"></iframe>
            </td>
        </tr>
    </table>
</body>
</html>
