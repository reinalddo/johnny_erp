<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS {$gsSubTitul}</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />

<body align:"center" id="top" style="font-family:'Arial'; ">

{report recordset=$agData record=rec	}

<table width="100%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >			  
			    <td class="colhead" colspan={$gsNumCols} align="center">{$gsEmpresa}</td>
			</tr>
			<tr >			  
	<td class="colhead" colspan={$gsNumCols} align="center">{$gsSubTitul}</td>			
</tr>
	<tr class="nowrap">
			<td>CODIGO</td>
			<td class="nowrap">NOMBRES </td>
			<td>CANT CAJAS</td>
			<td>PRECIO PAGADO</td>
			<td>VALOR FRUTA</td>
			<td>BONIFICACION</td>
			<td>COMPENSACIN</td>
			<td>EMPAQ.PAGDO</td>
			<td>OTROS_INGR.</td>
			<td>EMPAQ.COBR</td>
			<td>RET.FUENT</td>
			<td>ADELANTO</td>
			<td>ANTICIPOS</td>
			<td>PRESTAMOS</td>
			<td>INTER.PREST</td>
			<td>GTOS.ADMIN</td>
			<td>FERTILIZANT</td>
			<td>TRANSPORT</td>
			<td>INSUMOS</td>
			<td>TRANSFEREN</td>
			<td>VAL P/LIQ</td>
			<td>TCI</td>
			<td>OTROS.DESC</td>
			<td>NETO</td>
			<td>CHEQUE 1</td>
			<td>CHEQUE 2</td>
			</tr>
         </thead>	 
         <tbody>
{/report_header}


{report_detail}
	<tr class="nowraprow" style="text-align:right">
			<td>{$rec.C0}</td>
			<td style="text-align:left">{$rec.C1}</td>
			<td >{$rec.C2}</td>
			<td> {$rec.PrecioPagado}</td>
			<td>{$rec.D1}</td>
			<td>{$rec.D2}</td>
			<td>{$rec.D3}</td>
			<td>{$rec.D4}</td>
			<td>{$rec.D5}</td>
			<td>{$rec.D10}</td>
			<td>{$rec.D11}</td>
			<td>{$rec.D12}</td>
			<td>{$rec.D13}</td>
			<td>{$rec.D14}</td>
			<td>{$rec.D15}</td>
			<td>{$rec.D16}</td>
			<td>{$rec.D21}</td>
			<td>{$rec.D22}</td>
			<td>{$rec.D24}</td>
			<td>{$rec.D25}</td>
			<td>{$rec.D26}</td>
			<td>{$rec.D27}</td>
			<td>{$rec.D28}</td>
			<td>{$rec.TT}</td>
			<td>{$rec.E1}</td>
			<td>{$rec.E2}</td>
         </tr>
{/report_detail}


{report_footer}
		  <tr style="text-align:right"> 
			<td style="text-align:left" colspan=2>TOTALES:</td>
			<td>{$sum.C2|number_format:2}</td>
			
			{assign var=prom_precio value=$sum.D1+$sum.D2}
			<td>{$prom_precio/$sum.C2|number_format:2}</td>
		  
		  
			<td>{$sum.D1|number_format:2}</td>
			<td>{$sum.D2|number_format:2}</td>
			<td>{$sum.D3|number_format:2}</td>
			<td>{$sum.D4|number_format:2}</td>
			<td>{$sum.D5|number_format:2}</td>
			<td>{$sum.D10|number_format:2}</td>
			<td>{$sum.D11|number_format:2}</td>
			<td>{$sum.D12|number_format:2}</td>
			<td>{$sum.D13|number_format:2}</td>
			<td>{$sum.D14|number_format:2}</td>
			<td>{$sum.D15|number_format:2}</td>
			<td>{$sum.D16|number_format:2}</td>
			<td>{$sum.D21|number_format:2}</td>
			<td>{$sum.D22|number_format:2}</td>
			<td>{$sum.D24|number_format:2}</td>
			<td>{$sum.D25|number_format:2}</td>
			<td>{$sum.D26|number_format:2}</td>
			<td>{$sum.D27|number_format:2}</td>
			<td>{$sum.D28|number_format:2}</td>
			<td>{$sum.TT|number_format:2}</td>
		  </tr>
{/report_footer}






</tfoot>

</table>
{/report}
</body>
</html>


