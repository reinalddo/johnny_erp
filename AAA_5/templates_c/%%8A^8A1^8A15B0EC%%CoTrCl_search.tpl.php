<?php /* Smarty version 2.6.6, created on 2005-07-30 15:24:12
         compiled from CoTrCl_search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'CoTrCl_search.tpl', 73, false),)), $this); ?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<base target="_self">
<link rel="stylesheet" type="text/css" href="../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<title>CONCILIACION BANCARIA</title>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<script language="JavaScript">
//Begin CCS script
//Include Common JSFunctions @1-73ADA5ED
</script>
<script language="JavaScript">
</script>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../templates/Cabecera.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse" width="100%">
  <tr>
    <td align="middle">
      <form action="" method="post" name="fmSeleccion">
        <font class="CobaltFormHeaderFont">BUSCAR CONCILIACIONES </font>
        <table border="1" cellpadding="0" class="CobaltFormTABLE" cellspacing="0">
          <tr>
            <td class="CobaltFieldCaptionTD" nowrap>&nbsp;CUENTA&nbsp;</td>
            <td class="CobaltDataTD">&nbsp;
                <select class="CobaltSelect" name="selAuxiliar" style="FONT-SIZE:14; HEIGHT: 18px; WIDTH: 130px">
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
            </td>
          </tr>
           <tr>
            <td align="middle" class="CobaltFooterTD" colspan="27" nowrap>
            <input class="CobaltButton" name="btBuscar" type="submit" value="Buscar">&nbsp; </td>
          </tr>
        </table>
      </form>
      <!-- END Record movim_query -->
      <!-- BEGIN Grid movim_lista -->
      <font class="CobaltFormHeaderFont">CONCILIACIONES DISPONIBLES </font>
      <table border="1" cellpadding="3" cellspacing="1" border="0"  style="border-collapse:collapse" >
              <tr>
          <td class="CobaltFooterTD" colspan="11" nowrap>
            <!-- BEGIN Navigator Navigator -->
            <!-- BEGIN First_On --><a class="CobaltNavigatorLink" href="<?php echo $this->_tpl_vars['First_URL']; ?>
"><img border="0" src="../Themes/Cobalt/FirstOn.gif"></a> <!-- END First_On -->
            <!-- BEGIN Prev_On --><a class="CobaltNavigatorLink" href="<?php echo $this->_tpl_vars['Prev_URL']; ?>
"><img border="0" src="../Themes/Cobalt/PrevOn.gif"></a> <!-- END Prev_On -->&nbsp;<?php echo $this->_tpl_vars['Page_Number']; ?>
&nbsp;de
            <?php echo $this->_tpl_vars['Total_Pages']; ?>
&nbsp;
            <!-- BEGIN Next_On --><a class="CobaltNavigatorLink" href="<?php echo $this->_tpl_vars['Next_URL']; ?>
"><img border="0" src="../Themes/Cobalt/NextOn.gif"></a> <!-- END Next_On -->
            <!-- BEGIN Last_On --><a class="CobaltNavigatorLink" href="<?php echo $this->_tpl_vars['Last_URL']; ?>
"><img border="0" src="../Themes/Cobalt/LastOn.gif"></a> <!-- END Last_On --><!-- END Navigator Navigator -->&nbsp; </td>
        </tr>

        <tr align="middle">
          <td class="CobaltColumnTD" nowrap>
<?php echo '
            <!-- BEGIN Sorter Sorter_com_TipoComp --><a class="CobaltSorterLink" href="{Sort_URL}">FECHA</a><!-- END Sorter Sorter_com_TipoComp -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_FecTrans --><a class="CobaltSorterLink" href="{Sort_URL}">DB MARCADOS</a><!-- END Sorter Sorter_com_FecTrans -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_FecContab --><a class="CobaltSorterLink" href="{Sort_URL}">CR MARCADOS</a><!-- END Sorter Sorter_com_FecContab -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
            <!-- BEGIN Sorter Sorter_com_Usuario --><a class="CobaltSorterLink" href="{Sort_URL}">DIGITADO</a><!-- END Sorter Sorter_com_Usuario -->&nbsp;</td>
          <td class="CobaltColumnTD" nowrap>
'; ?>

        </tr>
        <!-- BEGIN Row -->
        <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['aDet']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <tr>
          <td class="CobaltDataTD">&nbsp;<a class="CobaltDataLink" href="<?php echo $this->_tpl_vars['aDet'][$this->_sections['j']['index']]['txt_Url']; ?>
" title="Ver Detalles de la Conciliacion"><?php echo ((is_array($_tmp=$this->_tpl_vars['aDet'][$this->_sections['j']['index']]['con_FecCorte'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%b-%d-%y") : smarty_modifier_date_format($_tmp, "%b-%d-%y")); ?>
</a>&nbsp;</td>
          <td class="CobaltDataTD" title="Suma dest DEBITOS marcados">&nbsp;<?php echo $this->_tpl_vars['aDet'][$this->_sections['j']['index']]['con_DebIncluidos']; ?>
&nbsp;</td>
          <td class="CobaltDataTD" title="Suma dest CREDITOS marcados">&nbsp;<?php echo $this->_tpl_vars['aDet'][$this->_sections['j']['index']]['con_CreIncluidos']; ?>
&nbsp;</td>
          <td class="CobaltDataTD" title="Fecha y Usuario que digitó">&nbsp;<?php echo $this->_tpl_vars['aDet'][$this->_sections['j']['index']]['txt_Digitado']; ?>
</td>
        </tr>

        <!-- END Row -->
        <?php endfor; else: ?>
        <!-- BEGIN NoRecords -->
        <tr>
          <td class="CobaltAltDataTD" colspan="11">No hay Detalles que Mostrar&nbsp;</td>
        </tr>
        <!-- END NoRecords -->
        <?php endif; ?>
        <tr height="5">
          <td class="CobaltFooterTD" colspan="11"  >
            <input  name="btNuevo" onclick="window.location.replace('../Co_Files/CoTrCl_mant.php?con_CodCuenta=1101020&con_CodAuxiliar=' + document.fmSeleccion.selAuxiliar.value )" type="button" title="CREA UNA NUEVA CONCILIACION PARA ESTE BANCO" value="NUEVA">
          </td>
        </tr>
      </table>
      <!-- END Grid movim_lista -->&nbsp;</p>
 </td> 
  </tr>
   <tr>
    <td>&nbsp;</td> 
  </tr>
</table>
</body>
</html>