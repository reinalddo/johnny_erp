<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  {literal}
  <style type="text/css">
        .tot{background-color:#D9D9D9;}
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CIERRE DE CAJA</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="orden,txt" resort=true}
{assign var=acum value=0}
{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        CIERRE DE CAJA<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">CAJA/BANCOS</td>
            <td class="headerrow"><strong>SDO/CAJA DEL DIA</strong></td>
            <td class="headerrow"><strong>SDO/INI LIBROS</strong></td>
            <td class="headerrow"><strong>(+) DEP.</strong></td>
            <td class="headerrow"><strong>(+) N/C</strong></td>
	    
	    <td class="headerrow"><strong>CH. N/A.</strong></td>
	    
	    <!-- <td class="headerrow"><strong>CH. CONFIRMADOS.</strong></td>
	    <td class="headerrow"><strong>CH. PAGADOS.</strong></td>-->
	    
            <td class="headerrow"><strong>CH. EMITIDOS.</strong></td>
            <td class="headerrow"><strong>(-) CHEQUES</strong></td>
            <td class="headerrow"><strong>N/D</strong></td>
            <td class="headerrow"><strong>SDO/FIN LIBRO BANCOS</strong></td>
            <td class="headerrow"><strong>SDO DISP. CAJA-BANCOS</strong></td>
        </tr>

{/report_header}


{report_detail}
    <tr>
        <td>{$rec.cod} - {$rec.txt}</td>
        {assign var=aux value=$rec.cod}
        {assign var=sal value=$agSaldos.$aux.caja|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.ant|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.dep|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.nc|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
	  
	  {assign var=sal value=$agSaldos.$aux.NA|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
	  
	  <!-- 
	  
	  {assign var=sal value=$agSaldos.$aux.CF|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
	  
	  
	  {assign var=sal value=$agSaldos.$aux.PG|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
	  
	  -->
	  
	  
	  
        {assign var=sal value=$agSaldos.$aux.emit|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
	  
	  
	  
	  
	  
        {assign var=sal value=$agSaldos.$aux.cheque|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	     <td class="colnum ">{$sal|number_format:2}</td> 
	  {/if}
        {assign var=sal value=$agSaldos.$aux.nd|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.final|default:0}
	  {if ($sal == 0)}
	    <td class="colnum "><strong>{0}</strong></td>
	  {else}
	    <td class="colnum "><strong>{$sal|number_format:2}</strong></td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.totfin|default:0}
	  {if ($sal == 0)}
	    <td class="colnum "><strong>{0}</strong></td>
	  {else}
	    <td class="colnum "><strong>{$sal|number_format:2}</strong></td>
	  {/if}
    </tr>
    
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;">
        <td style="font-style:italic;">Total</td>
        <!--<td></td>
        <td></td>-->
        {assign var=aux value=-1}
        {assign var=sal value=$agSaldos.$aux.caja|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.ant|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.dep|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.nc|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
	  
	  
	  
	  
	  {assign var=sal value=$agSaldos.$aux.NA|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
	  
	  <!-- 
	   {assign var=sal value=$agSaldos.$aux.CF|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
	  
	  
	   {assign var=sal value=$agSaldos.$aux.PG|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
	  
	  -->
	  	  
        {assign var=sal value=$agSaldos.$aux.anul|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.cheque|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.nd|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.final|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
        {assign var=sal value=$agSaldos.$aux.totfin|default:0}
	  {if ($sal == 0)}
	    <td class="colnum tot">{0}</td>
	  {else}
	    <td class="colnum tot">{$sal|number_format:2}</td>
	  {/if}
    </tr>
    </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
        <p style="line-height:0.5em;">{$agArchivo}</p>
    </div>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
{/report_footer}

{/report}
</body>