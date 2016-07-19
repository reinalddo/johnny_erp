<?php require_once('../../../Connections/c2.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$maxRows_c2 = 10;
$pageNum_c2 = 0;
if (isset($_GET['pageNum_c2'])) {
  $pageNum_c2 = $_GET['pageNum_c2'];
}
$startRow_c2 = $pageNum_c2 * $maxRows_c2;

mysql_select_db($database_c2, $c2);
$query_c2 = "SELECT * FROM propresupuesto";
$query_limit_c2 = sprintf("%s LIMIT %d, %d", $query_c2, $startRow_c2, $maxRows_c2);
$c2 = mysql_query($query_limit_c2, $c2) or die(mysql_error());
$row_c2 = mysql_fetch_assoc($c2);

if (isset($_GET['totalRows_c2'])) {
  $totalRows_c2 = $_GET['totalRows_c2'];
} else {
  $all_c2 = mysql_query($query_c2);
  $totalRows_c2 = mysql_num_rows($all_c2);
}
$totalPages_c2 = ceil($totalRows_c2/$maxRows_c2)-1;
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<title>presupuesto</title>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
</head>

<body>
<p><span class="CobaltPageBODY" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0">
  <script language="JavaScript" src="../LibJava/menu_func.js"></script>
</span></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table align="center" style:"border-colapse:collapse">
  <tr style="FONT-SIZE: x-small">
    <td style="FONT-SIZE: x-small" align="middle" valign="top"><form>
      <!-- BEGIN Grid SeGeUs_qry -->
      <font class="CobaltFormHeaderFont">PRESUPUESTO</font>
      <table width="464" border="0" cellpadding="1" cellspacing="1" style="WIDTH: 419px; HEIGHT: 159px">
        <tr align="middle">
          <td width="103" nowrap class="CobaltColumnTD"><!-- BEGIN Sorter Sorter_usu_IDusuario -->PERIODO</td>
          <td width="95" nowrap class="CobaltColumnTD"><!-- BEGIN Sorter Sorter_usu_login -->
               &nbsp;CUENTA</td>
          <td width="99" nowrap class="CobaltColumnTD">AUXILIAR</td>
          <td width="69" nowrap class="CobaltColumnTD">NIVEL</td>
          <td width="31" nowrap class="CobaltColumnTD">CANTIDAD</td>
          <td width="3" nowrap class="CobaltColumnTD">VALOR</td>
        </tr>
        <!-- BEGIN Row -->
        <?php do { ?>
          <tr align="middle" valign="top">
            <td class="CobaltDataTD"><?php echo $row_c2['pro_periodo']; ?></td>
            <td class="CobaltDataTD"><?php echo $row_c2['pro_cuenta']; ?></td>
            <td class="CobaltDataTD" nowrap><?php echo $row_c2['pro_auxiliar']; ?></td>
            <td class="CobaltDataTD"><?php echo $row_c2['pro_nivel']; ?></td>
            <td class="CobaltDataTD"><?php echo $row_c2['pro_cantidad']; ?></td>
            <td class="CobaltDataTD"><?php echo $row_c2['pro_valor']; ?></td>
          </tr>
          <?php } while ($row_c2 = mysql_fetch_assoc($c2)); ?>
        <!-- END Row -->
        <!-- BEGIN NoRecords -->
        <tr>
          <td colspan="6" class="CobaltDataTD">&nbsp;</td>
        </tr>
        <!-- END NoRecords -->
        <tr>
          <td aling="center" colspan="6" nowrap class="CobaltFooterTD"><!-- BEGIN Navigator Navigator -->
                <!-- BEGIN Prev_On -->
              <img src="../Themes/aqua/prevon.gif" alt="prev" width="24" height="14" border="0">
            <!-- END Prev_On -->
            &nbsp;
                <!-- BEGIN Next_On -->
              <img src="../Themes/aqua/nexton.gif" alt="sig" width="24" height="14" border="0">
            <!-- END Next_On -->
            <!-- END Navigator Navigator -->
            &nbsp; </td>
        </tr>
      </table>
      <a href="ingreso.php" class="CobaltFooterTD"">Agregar</a>&nbsp;
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($c2);
?>
