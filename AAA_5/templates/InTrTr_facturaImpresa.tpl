<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  <title> Factura </title>
</head>

<body id="top" style=" font:'helvetica'; !important;  font-size:10px;" onload="window.print();">
{report recordset=$agData record=rec resort=false}

{report_header}
    <!-- Logo -->
    {if $rec.conf_logo == 1}
        <table>
            <tr> <td> <img src= "{$rec.conf_rutalogo}"> </td> </tr>
        </table>
    {/if}
    <!-- CABECERA DEL DOCUMENTO -->
<table width="{$rec.conf_longdef}" cellspacing=0 style=" border:0; border-color: #504A4B ;solid 1px; text-align:left;font-size:11px;">
  <tr>
    <td>
      <hr>
{/report_header}
      
{report_detail}
<table>
        <tr>
            {if $rec.conf_c1 == 1} <td width="{$rec.conf_longc1}" > <strong>{$rec.conf_textoc1}</strong> {$rec.datoc1} </td> {/if}
            {if $rec.conf_c2 == 1} <td width="{$rec.conf_longc2}" > <strong>{$rec.conf_textoc2}</strong> {$rec.datoc2} </td> {/if}
            {if $rec.conf_c3 == 1} <td width="{$rec.conf_longc3}" > <strong>{$rec.conf_textoc3}</strong> {$rec.datoc3} </td> {/if}
            {if $rec.conf_c4 == 1} <td width="{$rec.conf_longc4}" > <strong>{$rec.conf_textoc4}</strong> {$rec.datoc4} </td> {/if}
            {if $rec.conf_c5 == 1} <td width="{$rec.conf_longc5}" > <strong>{$rec.conf_textoc5}</strong> {$rec.datoc5} </td> {/if}
            {if $rec.conf_c6 == 1} <td width="{$rec.conf_longc6}" > <strong>{$rec.conf_textoc6}</strong> {$rec.datoc6} </td> {/if}
            {if $rec.conf_c7 == 1} <td width="{$rec.conf_longc7}" > <strong>{$rec.conf_textoc7}</strong> {$rec.datoc7} </td> {/if}
            {if $rec.conf_c8 == 1} <td width="{$rec.conf_longc8}" > <strong>{$rec.conf_textoc8}</strong> {$rec.datoc8} </td> {/if}
            {if $rec.conf_c9 == 1} <td width="{$rec.conf_longc9}" > <strong>{$rec.conf_textoc9}</strong> {$rec.datoc9} </td> {/if}
            {if $rec.conf_c10 == 1} <td width="{$rec.conf_longc10}" > <strong>{$rec.conf_textoc10}</strong> {$rec.datoc10} </td> {/if}
</table>
{/report_detail}

{/report}
    <hr>
      <br>
    </td>
    </tr>
  </table>
  
<!-- DETALLE DEL DOCUMENTO -->
{report recordset=$agDet record=rec2 resort=false}
{report_header}
<table width="{$rec2.conf_longdef}" cellspacing=0 style=" border:0; border-color: #504A4B ; font-size:10px; text-align:left;">
        <tr>
            {if $rec2.conf_c1 == 1} <td width="{$rec2.conf_longc1}" > <strong>{$rec2.conf_textoc1}</strong> </td> {/if}
            {if $rec2.conf_c2 == 1} <td width="{$rec2.conf_longc2}"> <strong>{$rec2.conf_textoc2}</strong>  </td> {/if}
            {if $rec2.conf_c3 == 1} <td width="{$rec2.conf_longc3}" > <strong>{$rec2.conf_textoc3}</strong> </td> {/if}
            {if $rec2.conf_c4 == 1} <td width="{$rec2.conf_longc4}"> <strong>{$rec2.conf_textoc4}</strong>  </td> {/if}
            {if $rec2.conf_c5 == 1} <td width="{$rec2.conf_longc5}" > <strong>{$rec2.conf_textoc5}</strong> </td> {/if}
            {if $rec2.conf_c6 == 1} <td width="{$rec2.conf_longc6}" > <strong>{$rec2.conf_textoc6}</strong> </td> {/if}
            {if $rec2.conf_c7 == 1} <td width="{$rec2.conf_longc7}"> <strong>{$rec2.conf_textoc7}</strong>  </td> {/if}
            {if $rec2.conf_c8 == 1} <td width="{$rec2.conf_longc8}" > <strong>{$rec2.conf_textoc8}</strong> </td> {/if}
            {if $rec2.conf_c9 == 1} <td width="{$rec2.conf_longc9}"> <strong>{$rec2.conf_textoc9}</strong>  </td> {/if}
            {if $rec2.conf_c10 == 1} <td width="{$rec2.conf_longc10}" > <strong>{$rec2.conf_textoc10}</strong> </td> {/if}
        </tr>
</table>
{/report_header}

{report_detail}
<table width="{$rec2.conf_longdef}" cellspacing=0 style="  font-size:10px; text-align:left;">
        <tr>
            {if $rec2.conf_c1 == 1} <td width="{$rec2.conf_longc1}"> {$rec2.datoc1} </td> {/if}
            {if $rec2.conf_c2 == 1} <td width="{$rec2.conf_longc2}"> {$rec2.datoc2} </td> {/if}
            {if $rec2.conf_c3 == 1} <td width="{$rec2.conf_longc3}"> {$rec2.datoc3} </td> {/if}
            {if $rec2.conf_c4 == 1} <td width="{$rec2.conf_longc4}"> {$rec2.datoc4} </td> {/if}
            {if $rec2.conf_c5 == 1} <td width="{$rec2.conf_longc5}"> {$rec2.datoc5} </td> {/if}
            {if $rec2.conf_c6 == 1} <td width="{$rec2.conf_longc6}"> {$rec2.datoc6} </td> {/if}
            {if $rec2.conf_c7 == 1} <td width="{$rec2.conf_longc7}"> {$rec2.datoc7} </td> {/if}
            {if $rec2.conf_c8 == 1} <td width="{$rec2.conf_longc8}"> {$rec2.datoc8} </td> {/if}
            {if $rec2.conf_c9 == 1} <td width="{$rec2.conf_longc9}"> {$rec2.datoc9} </td> {/if}
            {if $rec2.conf_c10 == 1} <td width="{$rec2.conf_longc10}"> {$rec2.datoc10} </td> {/if}
        </tr>
</table>
{/report_detail}

{/report}
</body>
</html>