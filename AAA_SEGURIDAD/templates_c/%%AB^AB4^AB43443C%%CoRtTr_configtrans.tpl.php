<?php /* Smarty version 2.6.6, created on 2006-08-10 14:53:17
         compiled from CoRtTr_configtrans.tpl */ ?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<title>CONFIGURACION DE TRANSACCIONES DIMM</title>
<script language="JavaScript" src="../LibJava/general.js"></script>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
<script language="JavaScript" src="../LibJava/pajax-commom.js"></script>
<script language="JavaScript" src="../LibJava/pajax-parser.js"></script>
<script language="JavaScript" src="../LibJava/pajax-core.js"></script>
<script language="JavaScript" src="../LibJava/clsRpc.js"></script>
<script language="JavaScript" src="./CoRtTr_configtrans.js"></script>
<script language="JavaScript">
//Begin CCS script
//Include Common JSFunctions @1-73ADA5ED
</script>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY" >
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../templates/Cabecera.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- Record movim_query
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse" width="100%" align="center">
  <tr>
    <td align="middle">
      <form action="" method="post" name="fmSeleccion">
        <font class="CobaltFormHeaderFont">BUSCAR TRANSACCIONES </font>
        <table border="1" cellpadding="0" class="CobaltFormTABLE" cellspacing="0">
          <tr>
            <td class="CobaltFieldCaptionTD" nowrap>&nbsp;CUENTA&nbsp;</td>
            <td class="CobaltDataTD">&nbsp;
                <select class="CobaltSelect" name="selAuxiliar" style="font-size: 9; height: 17; width: 95">
                <option value="">- - - - - - - - - - - - </option>
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['aAux']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                    <option value="<?php echo $this->_tpl_vars['aAux'][$this->_sections['i']['index']]['per_CodAuxiliar']; ?>
" <?php if ($this->_tpl_vars['aAux'][$this->_sections['i']['index']]['per_CodAuxiliar'] == $_REQUEST['selAuxiliar']): ?> selected <?php endif; ?>  >
                            <?php echo $this->_tpl_vars['aAux'][$this->_sections['i']['index']]['txt_Nombre']; ?>

                    </option>
                 <?php endfor; endif; ?>
              </select>
            </td>
          </tr>
           <tr>
            <td align="middle" class="CobaltFooterTD" colspan="27" nowrap>
            <input class="CobaltButton" name="btBuscar" type="submit" value="Buscar">&nbsp; </td>
          </tr>
        </table>
      </form>
 -->
      <!-- BEGIN Grid movim_lista -->
      <font class="CobaltFormHeaderFont" align="center">- TRANSACCIONES DISPONIBLES -</font>
      <table border="0" cellpadding="3" cellspacing="1" style="border-collapse: collapse"  align="center">
        <tr align="middle" >
          <td  nowrap width=""></td>
          <td class="CobaltColumnTD"  colspan=<?php echo $this->_tpl_vars['numCols']; ?>
 style="text-align:center">TRANSACCION </td>
        </tr>
        <tr align="middle" >
          <td class="CobaltColumnTD"  nowrap width="">TIPO DE COMPROBANTE</td>
          <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['aNomCols']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
          <td class="CobaltColumnTD" align="center" nowrap width="60"><?php echo $this->_tpl_vars['aNomCols'][$this->_sections['j']['index']][1]; ?>
</td>
          <?php endfor; endif; ?>
        </tr>
      <!-- BEGIN Row -->
      
        <?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['aRowData']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['row']['show'] = true;
$this->_sections['row']['max'] = $this->_sections['row']['loop'];
$this->_sections['row']['step'] = 1;
$this->_sections['row']['start'] = $this->_sections['row']['step'] > 0 ? 0 : $this->_sections['row']['loop']-1;
if ($this->_sections['row']['show']) {
    $this->_sections['row']['total'] = $this->_sections['row']['loop'];
    if ($this->_sections['row']['total'] == 0)
        $this->_sections['row']['show'] = false;
} else
    $this->_sections['row']['total'] = 0;
if ($this->_sections['row']['show']):

            for ($this->_sections['row']['index'] = $this->_sections['row']['start'], $this->_sections['row']['iteration'] = 1;
                 $this->_sections['row']['iteration'] <= $this->_sections['row']['total'];
                 $this->_sections['row']['index'] += $this->_sections['row']['step'], $this->_sections['row']['iteration']++):
$this->_sections['row']['rownum'] = $this->_sections['row']['iteration'];
$this->_sections['row']['index_prev'] = $this->_sections['row']['index'] - $this->_sections['row']['step'];
$this->_sections['row']['index_next'] = $this->_sections['row']['index'] + $this->_sections['row']['step'];
$this->_sections['row']['first']      = ($this->_sections['row']['iteration'] == 1);
$this->_sections['row']['last']       = ($this->_sections['row']['iteration'] == $this->_sections['row']['total']);
?>
		<tr valign="top">
		    <td ><?php echo $this->_tpl_vars['aRowData'][$this->_sections['row']['index']][0]; ?>
 - <?php echo $this->_tpl_vars['aRowData'][$this->_sections['row']['index']][1]; ?>
</td>
			<?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['start'] = (int)2;
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['aRowData'][$this->_sections['row']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
if ($this->_sections['col']['start'] < 0)
    $this->_sections['col']['start'] = max($this->_sections['col']['step'] > 0 ? 0 : -1, $this->_sections['col']['loop'] + $this->_sections['col']['start']);
else
    $this->_sections['col']['start'] = min($this->_sections['col']['start'], $this->_sections['col']['step'] > 0 ? $this->_sections['col']['loop'] : $this->_sections['col']['loop']-1);
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = min(ceil(($this->_sections['col']['step'] > 0 ? $this->_sections['col']['loop'] - $this->_sections['col']['start'] : $this->_sections['col']['start']+1)/abs($this->_sections['col']['step'])), $this->_sections['col']['max']);
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
			<td align="center"><input value = "<?php echo $this->_tpl_vars['aRowData'][$this->_sections['row']['index']][$this->_sections['col']['index']]; ?>
" size=2 maxsize=2 class="CobaltInputSB2"
				  onchange="fCambioDato(this, <?php echo $this->_tpl_vars['aRowData'][$this->_sections['row']['index']][0]; ?>
, <?php echo $this->_sections['col']['iteration']; ?>
)"></input>
			</td>

			<?php endfor; endif; ?>
		</tr>
		<?php endfor; endif; ?>

      

      </table>
      <!-- END Grid movim_lista -->&nbsp;
 </td> 
  </tr>
   <tr>
    <td>&nbsp;</td> 
  </tr>
</table>
</body>
</html>