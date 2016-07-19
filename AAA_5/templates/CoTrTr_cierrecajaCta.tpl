<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Cierre de Caja por cuenta contable -->
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
{*{report recordset=$agData record=rec groups="orden,txt" resort=true}*}
{report recordset=$agData record=rec resort=false}
{assign var=acum value=0}
{report_header}
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
            <td class="headerrow"><strong>SDO/INICIAL</strong></td>
            <td class="headerrow"><strong>(+) DEP.</strong></td>
            <td class="headerrow"><strong>(+) N/C</strong></td>
	    <td class="headerrow" style="background-color:#E6E6E6;"><strong>CH. N/A.</strong></td>
            <td class="headerrow" style="background-color:#E6E6E6;"><strong>CH. EMITIDOS <br> CUSTODIA.</strong></td>
            <td class="headerrow"><strong>(-) CHEQUES <br> GIRADOS</strong></td>
            <td class="headerrow"><strong>N/D</strong></td>
            <td class="headerrow"><strong>SDO/FINAL</strong></td>
        </tr>

{/report_header}


{report_detail}
    <tr>
	  <td class="coldata "> {$rec.per_CodAuxiliar} - {$rec.aux}</td>
	  <td class="colnum "> {$rec.INI|number_format:2|default:0}</td>
	  <td class="colnum "> {$rec.DEP|number_format:2|default:0}</td>
	  <td class="colnum "> {$rec.NC|number_format:2|default:0}</td>
	  <td class="colnum "style="background-color:#E6E6E6;color:#424242;"> {$rec.NA|number_format:2|default:0}</td>
	  <td class="colnum "style="background-color:#E6E6E6;color:#424242;"> {$rec.EMI|number_format:2|default:0}</td>
	  <td class="colnum "> {$rec.CHEQUES|number_format:2|default:0}</td>
	  <td class="colnum "> {$rec.ND|number_format:2|default:0}</td>
	  <td class="colnum "><strong>{$rec.saldo|number_format:2|default:0}</strong></td>
	  
	  {assign var=Tini	value=$Tini+$rec.INI}
	  {assign var=TDEP	value=$TDEP+$rec.DEP}
	  {assign var=TNC	value=$TNC+$rec.NC}
	  {assign var=TNA	value=$TNA+$rec.NA}
	  {assign var=TEMI	value=$TEMI+$rec.EMI}
	  {assign var=TCHEQUES	value=$TCHEQUES+$rec.CHEQUES}
	  {assign var=TND	value=$TND+$rec.ND}
	  {assign var=Tsaldo	value=$Tsaldo+$rec.saldo}
    </tr>
{/report_detail}

{report_footer}
<tr>
	  <td class="coldata"style="background-color:#BDBDBD;"><strong> TOTAL:</td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$Tini|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TDEP|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TNC|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TNA|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TEMI|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TCHEQUES|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$TND|number_format:2|default:0}</strong></td>
	  <td class="colnum "style="background-color:#BDBDBD;"><strong> {$Tsaldo|number_format:2|default:0}</strong></td>
</tr>
 </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
        <p style="line-height:0.5em;">{$agArchivo}</p>
    </div>
{/report_footer}

{/report}
</body>
