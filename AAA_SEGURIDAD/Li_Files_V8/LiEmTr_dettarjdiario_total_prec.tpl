<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS {$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="../css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="../css/AAA_tablas_print.css">
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData groups="txp_Producto, txp_Shipper, txp_NombZona " record=rec}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr>
			<td>CODIGO</td>
			<td>PRODUCTOR</td>
			<td>MARCA</td>
			<td>TARJA #</td>
			<td>CJS EMBARC.</td>
			<td>CANT A PAGAR</td>
			<td>PREC OFICI</td>
			<td>BONIFIC</td>
			<td>DIF PRECIO</td>
			<td>PRECIO PACTADO</td>
			<td>VALOR</td>	
			</tr>
         </thead>	 
{/report_header}
{report_header group ="txp_Producto" }
<tr>
  <td colspan=11 style="height: 35px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">PRODUCTO: {$rec.txp_Producto}                         <td>
</tr>
{/report_header}

{report_header group ="txp_Shipper" }
<tr>
  <td colspan=11 style="height: 25px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">SHIPPER: {$rec.txp_Shipper}                         <td>
</tr>
{/report_header}
{report_header group ="txp_NombZona" }
<tr>
  <td colspan=11 style="height: 20px; vertical-align:bottom; font-size:10; font-weight:bold; text-align:center">ZONA: {$rec.txp_NombZona}                         <td>
</tr>
{/report_header}

{report_detail}
	<tr style="white-space:nowrap">
			<td>{$rec.txp_Embarcador}</td>
			<td>{$rec.txp_Productor}</td>
			<td>{$rec.txp_Marca}</td>
			<td>{$rec.txp_NumTarja}</td>
			<td class="colnum">{$rec.txp_CantNeta|number_format:0}</td>
			<td class="colnum">{$rec.txp_CantPagar|number_format:0}</td>
			<td class="colnum">{$rec.txp_ValUnitario|number_format:4}</td>
			<td class="colnum">{$rec.txp_Bono|number_format:4}</td>
			<td class="colnum">{$rec.txp_DifPrec|number_format:4}</td>
			<td class="colnum">{$rec.txp_PrecUnit|number_format:4}</td>
			<td class="colnum">{$rec.txp_ValTotal|number_format:2}</td>
			
         </tr>
{/report_detail}

{report_footer group ="txp_NombZona" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td colspan="4">SUMA {$rec.txp_NombZona} </td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum">{$sum.txp_CantPagar|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum" title="Precio Promedio Ponderado">{$flProm|number_format:4}</td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>
{/report_footer}


{report_footer group ="txp_Shipper" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td colspan="4">SUMA {$rec.txp_Shipper} </td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum">{$sum.txp_CantPagar|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum" title="Precio Promedio Ponderado">{$flProm|number_format:4}</td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>

{/report_footer}

{report_footer group ="txp_Producto" }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td colspan="4">SUMA {$rec.txp_Producto} </td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum">{$sum.txp_CantPagar|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum" title="Precio Promedio Ponderado">{$flProm|number_format:4}</td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>

{/report_footer}


{report_footer  }
  {assign var=flProm value = $sum.txp_ValTotal/$sum.txp_CantPagar }
  <tr style="white-space:nowrap">
      <td colspan="4">SUMA SEMANA {$rec.txp_Semana} </td>
      <td class="colnum">{$sum.txp_CantNeta|number_format:0}</td>
      <td class="colnum">{$sum.txp_CantPagar|number_format:0}</td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum"> </td>
      <td class="colnum" title="Precio Promedio Ponderado">{$flProm|number_format:4}</td>
      <td class="colnum">{$sum.txp_ValTotal|number_format:2}</td>
   </tr>
{/report_footer}

</table>
{/report}
</body>
</html>
