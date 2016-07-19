<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DESPACHO SEMANAL {$gsSubTitul}</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">

{report recordset=$agData record=rec	groups="bodega, productor" resort=true}

<table width="100%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=10 align="center">REPORTE DE DESPACHO SEMANAL</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=10 align="center">MODULO INVENTARIO</td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=10 align="center">SEMANA: {$rec.semana} </td>
			</tr>
	      		    
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>  
			<td colspan=2 align="left">Item</td>
			<td># Unidades</td>
			<td>Precio </td>
			<td>Total</td>
			</tr>
         </thead>	 
         <tbody>
{/report_header}

{report_header group="bodega"}			
  		<tr>
			<td height="40" class="colhead" style="vertical-align: bottom;  text-align:center" colspan=10 > Bodega: {$rec.bodega}</td>
       		</tr>
{/report_header}
{report_header group="productor"}			
  		<tr>
			<td height="40"  align="left" colspan=10 style="vertical-align: bottom"> Productor: {$rec.productor}</td>
       		</tr>

{/report_header}

{report_detail}
	<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>  
			<td>{$rec.coditem}</td>
			<td>{$rec.item}</td>
			<td>{$rec.cantidad}</td>
			<td>{$rec.precio}</td>
			<td>{$rec.total}</td>
         </tr>
{/report_detail}


</tfoot>

</table>
{/report}
</body>
</html>
