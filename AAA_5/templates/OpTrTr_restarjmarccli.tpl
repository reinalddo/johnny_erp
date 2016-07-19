<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!-- Resumen de tarjas por Cliente y marca -->
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/general_print.css" title="CSS parea impresion" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS parea impresion" />
  <title>RESUMEN POR CLIENTE Y MARCA</title>

<style type="text/css">
</head>

<body id="top" style="font-family:'Arial'>
{report recordset=$agData record=rec	groups="vva_descripcion, txt_vapor" resort=true}

{report_header}
<div id="container">
  <div class="tableContainer">

    <hr/>
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:16pt; font-weight:bold"> {$rec.txt_embarque}</span>
	<table cellspacing="0">

{/report_header}

{report_header group="vva_descripcion"}
	  <thead>
		<tr ><td colspan={$agNumCols} align="left" style="text-align: 'left'">VAPOR: {$rec.vva_descripcion|upper}</td></tr>
		<tr><td class="headerrow" style="width:200px;"></td>
			{foreach key=key item=col from=$agCabeGru}
				  <td class="colhead headerrow"  colspan={$col.long}>{$key|upper}</td>
			{/foreach}
			<td class="colhead headerrow" >TOTAL</td>
		</tr>
		<tr><td class="headerrow" style="width:2000px;" >PRODUCTOR / CODIGOS</td>
			{foreach key=key item=col from=$agAbrevia}
				  <td class="colhead headerrow" >{$col|upper}</td>
			{/foreach}
			<td class="colhead headerrow" >TOTAL</td>
		</tr>
	  </thead>
	  <tfoot>
		  <tr> 
			<td colspan={$agNumCols}></td>
		  </tr>
	  </tfoot>
{/report_header}
	  <tbody>
{report_detail}
		<tr>
		  <td style="font-size:9px; width=300px">{$rec.txt_nombre|upper} &nbsp;/&nbsp; &nbsp; {$rec.txt_codigos|upper}</td>
			{foreach key=key item=col from=$agNombCol}
				<td class="col120 colnum" >{if  $rec.$col eq 0}{else}{$rec.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="colnum col120"></td>
	    </tr>
{/report_detail}

{report_footer group="vva_descripcion"}
		<tr>
			<td>TOTAL</td>
				{foreach key=key item=col from=$agNombCol}
					<td class="col80 colnum" >{$sum.$col|number_format:0}</td>
				{/foreach}
		</tr>
{/report_footer}
	  </tbody>
	</table>  
{/report}
  </div>
</div> <!-- end container -->
</body>



