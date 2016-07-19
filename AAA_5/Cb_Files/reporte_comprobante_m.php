<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<script>
function imprimirPagina() {
  if (window.print)
  {
	document.all.imprimir.style.visibility='hidden'  
    window.print();
	}
  else
  {
    alert("Lo siento, pero a tu navegador no se le puede ordenar imprimir" +
      " desde la web. Actualizate o hazlo desde los menús");
  }
}
</script>

</head>

<body>
<form name="all">
<?php
	$variable1="Servicios Mariscal";
	$variable2="8 de Enero de 2009";
	$variable3="0991343997001";
	$variable4="Factura";
	$variable5="Jose Mascote 4925 entre Sedalana";
	$variable6="001-001-0010521";
	$variable7="2009";
	$variable8="153.00";
	$variable9="Renta";
	$variable10="329";
	$variable11="2.00%";
	$variable12="3.06";
	$variable13="3.06";
							
?>
	<DIV STYLE="POSITION:absolute; LEFT:90px; TOP:110px;;"><?php echo $variable1?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:780px; TOP:110px;"><?php echo $variable2?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:135px; TOP:135px;"><?php echo $variable3?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:925px; TOP:135px;"><?php echo $variable4?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:135px; TOP:162px;"><?php echo $variable5?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:925px; TOP:162px; "><?php echo $variable6?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:70px; TOP:250px; "><?php echo $variable7?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:265px; TOP:250px;"><?php echo $variable8?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:496px; TOP:250px; "><?php echo $variable9?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:650px; TOP:250px; "><?php echo $variable10?></DIV>
   	<DIV STYLE="POSITION:absolute; LEFT:880px; TOP:250px; "><?php echo $variable11?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:1105px; TOP:250px; "><?php echo $variable12?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:1105px; TOP:360px; "><?php echo $variable13?></DIV>

<table width="959" border="0" cellpadding="0" cellspacing="0"> 
  <tr>
    <td width="433" height="302">&nbsp;</td>
    <td width="65">&nbsp;</td>
    <td width="461">&nbsp;</td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"><input name="imprimir" id="noprint" type="button" onClick="imprimirPagina();" value="Imprimir"></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="57">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
