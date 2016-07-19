<?php session_start();
/*    Reporte de Inconsistencias. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *
 */
ini_set("memory_limit", "512M");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);

//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
//End Include Common Files
include_once("./../De_Files/Cabecera.php");
//End Include Page implementation
$DBdatos = new clsDBdatos();

if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}
if (1 == $_SESSION["atr"]["InTrTr"]["CON"]){
   include("../LibPhp/excelOut.php");
}

  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_POST["filter"])) $filter = @$_POST["filter"];
  if (isset($_POST["filter_field"])) $filterfield = @$_POST["filter_field"];
  $wholeonly = false;
  if (isset($_POST["wholeonly"])) $wholeonly = @$_POST["wholeonly"];

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];

?>

<html>
<head>
<title>Smart ERP V.8.1</title>
<meta name="generator" http-equiv="content-type" content="text/html; charset=UTF-8">
<style type="text/css">
  body {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .bd {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .tbl {
    background-color: #FFFFFF;
  }
  a:link { 
    background-color: #FFFFFF01;
    color: #FF0000;
    font-family: Arial;
    font-size: 12px;
  }
  a:active { 
    color: #0000FF;
    font-family: Arial;
    font-size: 12px;
  }
  a:visited { 
    color: #800080;
    font-family: Arial;
    font-size: 12px;
  }
  .hr {
    background-color: #336699;
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:link {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:active {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:visited {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  .dr {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
  .sr {
    background-color: #FFFFCF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
</style>
</head>
<body>
<table class="bd" width="70%"><tr><td class="hr"><h2>Comprobantes de INVENTARIO inconsistentes</h2></td></tr></table>
<?php
  $conn = connect();
  $showrecs = 25;
  $pagerange = 10;

  $a = @$_GET["a"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  select();

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;

  mysql_close($conn);
?>
<table class="bd" width="70%"><tr><td class="hr">Construyendo Negocios Inteligentes   -   http://www.erp-smart.com    </td></tr></table>
</body>
</html>

<?php function select()
  {
  global $a;
  global $showrecs;
  global $page;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $order;
  global $ordtype;


  if ($a == "reset") {
    $filter = "";
    $filterfield = "";
    $wholeonly = "";
    $order = "";
    $ordtype = "";
  }

  $checkstr = "";
  if ($wholeonly) $checkstr = " checked";
  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>

<hr size="1" noshade>
<form action="InTrTr_inconsistencias.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>FILTROS</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">Todos los Campos</option>
<option value="<?php echo "TIPO" ?>"<?php if ($filterfield == "TIPO") { echo "selected"; } ?>><?php echo htmlspecialchars("TIPO") ?></option>
<option value="<?php echo "COMPROBANTE" ?>"<?php if ($filterfield == "COMPROBANTE") { echo "selected"; } ?>><?php echo htmlspecialchars("COMPROBANTE") ?></option>
<option value="<?php echo "REGISTRO" ?>"<?php if ($filterfield == "REGISTRO") { echo "selected"; } ?>><?php echo htmlspecialchars("REGISTRO") ?></option>
<option value="<?php echo "TRANSACCION" ?>"<?php if ($filterfield == "TRANSACCION") { echo "selected"; } ?>><?php echo htmlspecialchars("TRANSACCION") ?></option>
<option value="<?php echo "CONTABILIDAD" ?>"<?php if ($filterfield == "CONTABILIDAD") { echo "selected"; } ?>><?php echo htmlspecialchars("CONTABILIDAD") ?></option>
<option value="<?php echo "EMISOR" ?>"<?php if ($filterfield == "EMISOR") { echo "selected"; } ?>><?php echo htmlspecialchars("EMISOR") ?></option>
<option value="<?php echo "RECEPTOR" ?>"<?php if ($filterfield == "RECEPTOR") { echo "selected"; } ?>><?php echo htmlspecialchars("RECEPTOR") ?></option>
<option value="<?php echo "LIBRO" ?>"<?php if ($filterfield == "LIBRO") { echo "selected"; } ?>><?php echo htmlspecialchars("LIBRO") ?></option>
<option value="<?php echo "DIGITADOR" ?>"<?php if ($filterfield == "DIGITADOR") { echo "selected"; } ?>><?php echo htmlspecialchars("DIGITADOR") ?></option>
<option value="<?php echo "DIGITADO" ?>"<?php if ($filterfield == "DIGITADO") { echo "selected"; } ?>><?php echo htmlspecialchars("DIGITADO") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Palabra Justa</td>
</td>
<td><input type="submit" name="action" value="Aplicar"></td>
<td><a href="InTrTr_inconsistencias.php?a=reset">RESETEAR</a></td>
<td>  Registros mostrados <?php echo $startrec + 1 ?> - <?php echo $reccount ?> of <?php echo $count ?></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "TIPO" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("TIPO") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "COMPROBANTE" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("COMPROBANTE") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "REGISTRO" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("REGISTRO") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "TRANSACCION" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("TRANSACCION") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "CONTABILIDAD" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("CONTABILIDAD") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "EMISOR" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("EMISOR") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "RECEPTOR" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("RECEPTOR") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "LIBRO" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("LIBRO") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "DIGITADOR" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("DIGITADOR") ?></a></td>
<td class="hr"><a class="hr" href="InTrTr_inconsistencias.php?order=<?php echo "DIGITADO" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("DIGITADO") ?></a></td>
</tr>
<?php
  for ($i = $startrec; $i < $reccount; $i++)
  {
    $row = mysql_fetch_assoc($res);
    $style = "dr";
    if ($i % 2 != 0) {
      $style = "sr";
    }
?>
<tr>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["TIPO"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["COMPROBANTE"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["REGISTRO"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["TRANSACCION"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["CONTABILIDAD"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["EMISOR"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["RECEPTOR"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["LIBRO"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["DIGITADOR"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["DIGITADO"]) ?></td>
</tr>
<?php
  }
  mysql_free_result($res);
?>
</table>
<br>
<?php showpagenav($page, $pagecount); ?>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<?php if ($page > 1) { ?>
<td><a href="InTrTr_inconsistencias.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
<?php } ?>
<?php
  global $pagerange;

  if ($pagecount > 1) {

  if ($pagecount % $pagerange != 0) {
    $rangecount = intval($pagecount / $pagerange) + 1;
  }
  else {
    $rangecount = intval($pagecount / $pagerange);
  }
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);

    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<td><b><?php echo $j ?></b></td>
<?php } else { ?>
<td><a href="InTrTr_inconsistencias.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="InTrTr_inconsistencias.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="InTrTr_inconsistencias.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function connect()
{
  $conn = mysql_connect("localhost", "root", "xx")or die (mysql_error()); //$_HOST,$_USER,$_PASS $_SESSION; //
  mysql_select_db($_DB);
  return $conn;
}

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;
  
  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT * FROM (SELECT com_tipocomp AS TIPO, com_numcomp AS COMPROBANTE, com_regnumero AS REGISTRO, com_fectrans AS TRANSACCION,com_feccontab AS CONTABILIDAD, com_emisor AS CODEMI,  (SELECT CONCAT(per_apellidos, ' ',per_nombres) FROM conpersonas WHERE per_codauxiliar = com_emisor) AS EMISOR,com_codreceptor AS CODRECEPTA,  (SELECT CONCAT(per_apellidos, ' ',per_nombres) FROM conpersonas WHERE per_codauxiliar = com_codreceptor) AS RECEPTOR, com_libro AS CODLIB,  (SELECT par_descripcion FROM genparametros WHERE par_clave= 'CLIBRO' AND par_secuencia = com_libro) AS LIBRO, com_usuario AS DIGITADOR, com_fecdigita AS DIGITADO  FROM concomprobantes   WHERE com_tipocomp IN (SELECT par_valor3 FROM genparametros WHERE par_clave= 'CLIBRO') AND (com_libro = '9999' OR com_emisor = 0 OR com_codreceptor=0)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (TIPO like '" .$filterstr ."') or (COMPROBANTE like '" .$filterstr ."') or (REGISTRO like '" .$filterstr ."') or (TRANSACCION like '" .$filterstr ."') or (CONTABILIDAD like '" .$filterstr ."') or (EMISOR like '" .$filterstr ."') or (RECEPTOR like '" .$filterstr ."') or (LIBRO like '" .$filterstr ."') or (DIGITADOR like '" .$filterstr ."') or (DIGITADO like '" .$filterstr ."')";
  }
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysql_query($sql, $conn) or die(mysql_error());
//  $res = $db->execute($sql);   //JVL
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT COUNT(*) FROM (SELECT com_tipocomp AS TIPO, com_numcomp AS COMPROBANTE, com_regnumero AS REGISTRO, com_fectrans AS TRANSACCION,com_feccontab AS CONTABILIDAD, com_emisor AS CODEMI,  (SELECT CONCAT(per_apellidos, ' ',per_nombres) FROM conpersonas WHERE per_codauxiliar = com_emisor) AS EMISOR,com_codreceptor AS CODRECEPTA,  (SELECT CONCAT(per_apellidos, ' ',per_nombres) FROM conpersonas WHERE per_codauxiliar = com_codreceptor) AS RECEPTOR, com_libro AS CODLIB,  (SELECT par_descripcion FROM genparametros WHERE par_clave= 'CLIBRO' AND par_secuencia = com_libro) AS LIBRO, com_usuario AS DIGITADOR, com_fecdigita AS DIGITADO  FROM concomprobantes   WHERE com_tipocomp IN (SELECT par_valor3 FROM genparametros WHERE par_clave= 'CLIBRO') AND (com_libro = '9999' OR com_emisor = 0 OR com_codreceptor=0)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (TIPO like '" .$filterstr ."') or (COMPROBANTE like '" .$filterstr ."') or (REGISTRO like '" .$filterstr ."') or (TRANSACCION like '" .$filterstr ."') or (CONTABILIDAD like '" .$filterstr ."') or (EMISOR like '" .$filterstr ."') or (RECEPTOR like '" .$filterstr ."') or (LIBRO like '" .$filterstr ."') or (DIGITADOR like '" .$filterstr ."') or (DIGITADO like '" .$filterstr ."')";
  }
  $res = mysql_query($sql, $conn) or die(mysql_error());
//  $res = $db->execute($sql);   //JVL
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} ?>
