<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="author" content="Fausto Astudillo" />
    <link rel="stylesheet" type="text/css" href="../css/report.css" title="Default CSS Sheet" />
    <title>CONTENEDORES</title>
  </head>

  <body id="top">

{report recordset=$agData record=rec	groups="txt_naviera, txt_embarque, txt_contenedor, txt_producto, txt_productor, txt_marcemp, txt_empacador" resort=true}

{report_header}
    <hr/>
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:16; font-weight:bold"> {$rec.txt_embarque}</span>
    <table border=1 cellspacing=0 >

{/report_header}

{report_header group="txt_contenedor"}
    <tr ><td colspan=5>CONTENEDOR {$rec.txt_tipo}: {$rec.txt_contenedor}</td><td colspan=1>{$rec.txt_naviera}</td></tr>
    <tr ><td colspan=6>SELLO: {$rec.region}</td></tr>
    <tr><td class="colhead headerrow">PRODUCTOR</td>
	    <td class="colhead headerrow">CODIGOS<td class="colhead headerrow">
        {section name=col loop=$agCabeceras}
            {$agCabeCeras[$col]}</td><td class="colhead headerrow">
        {/section}
        TOTAL</td></tr>
{/report_header}

{report_header group = "txt_productor"} 
	{assign var="tplCodigos" value=""}
	{section name=col loop=$agMarcas}
		{set var=$agSumas.$col value=''}
  {/section}
{/report_header}
{report_header group = "txt_empacador"}
	{concat var="tplCodigos" value=", "}
	{concat var="tplCodigos" value=$rec.txt_empacador}
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.txt_productor}</td><td class="colnum ">{$tplCodigos}</td>
        {foreach key=key item=col from=$agCols}
            <td class="colnum ">{$rec.$col|number_format:0}</td>
        {/foreach}
        <td class="colnum ">{$rec.c_total|number_format:0}</td>
    </tr>
{/report_detail}

{*
{report_footer group="txt_productor"}
    <tr>
        <td nowrap>{$rec.txt_productor} </td><td>{$tplCodigos|default:"--"}</td>
        	{foreach key=key item=col from=$agSumas}
    			<td style="font-size:11px; text-align:right">{$col|number_format:0}{set var=$agSumas.$key value=''}</td>
  			{/foreach}
    </tr>
{/report_footer}
*}
{report_footer group="txt_contenedor"}
    <tr >
        <td>TOTALES </td><td> - -</td>
            {foreach key=key item=col from=$agCols}
                <td class="colnum">{$sum.$col}</td>
            {/foreach}
        <td class="colnum headerrow">{$sum.c_total}</td>
    </tr>
{/report_footer}

{report_footer}
    <tr>
    </tr>
    </table>
    <table>
    	<tr>
    		<td>RESUMEN DE MARCAS</td><td>TOTAL</td><td>PORCENTAJE</td>
    	</tr>
    	{foreach name=outer item=linea from=$agResumen}
    	<tr>
  			{foreach key=key item=col from=$linea}
    			<td>{$col}</td>
  			{/foreach}
    	</tr>
		{/foreach}
    </table>
    <hr/>

{/report_footer}

{/report}
</body>



