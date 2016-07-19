<?php /* Smarty version 2.6.18, created on 2015-04-07 12:59:19
         compiled from CoTrTr_movimientoslistado.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_movimientoslistado.tpl', 17, false),array('block', 'report_header', 'CoTrTr_movimientoslistado.tpl', 20, false),array('block', 'report_detail', 'CoTrTr_movimientoslistado.tpl', 50, false),array('block', 'report_footer', 'CoTrTr_movimientoslistado.tpl', 68, false),array('modifier', 'number_format', 'CoTrTr_movimientoslistado.tpl', 63, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  @rev esl 14-Sep-2012 Cambiar glosa del detalle por glosa de concepto - Solicitado por Wachito para Asisbane
      @rev esl 19/oct/2012 Reporte de movimientos sin grupos, solo una lista
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "det_CodCuenta, det_CodAuxiliar, com_FecContab,txt_Compr",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->assign('acum', 0); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        LISTADO DE MOVIMIENTOS DE CUENTAS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    <td class="headerrow">COD CUENTA</td>
	    <td class="headerrow"><strong>CUENTA</strong></td>
	    <td class="headerrow"><strong>COD AUXILIAR</strong></td>
	    <td class="headerrow"><strong>COD ANTERIOR</strong></td>
	    <td class="headerrow"><strong>AUXILIAR</strong></td>
	    <td class="headerrow"><strong>REF OPERATIVA</strong></td>
	    <td class="headerrow">COMP</td>
	    <td class="headerrow"><strong>CHEQ</strong></td>
	    <td class="headerrow"><strong>FECHA</strong></td>
	    <td class="headerrow"><strong>BENEFICIARIO</strong></td>
	    <td class="headerrow"><strong>GLOSA</strong></td>
	    <td class="headerrow"><strong>DEBITO</strong></td>
	    <td class="headerrow"><strong>CREDITO</strong></td>
	</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'det_CodAuxiliar')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['det_CodCuenta']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['txt_NombCuenta']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['det_CodAuxiliar']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['per_codAnterior']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['txt_NombAuxiliar']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['det_RefOperativa']; ?>
</td>
    
	<td nowrap><?php echo $this->_tpl_vars['rec']['txt_Compr']; ?>
</td><td class="colnum "><?php echo $this->_tpl_vars['rec']['com_Cheque']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['beneficiario']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['CON']; ?>
</td> <!-- @rev esl 14-Sep-2012 Cambiar glosa del detalle por glosa de concepto - Solicitado por Wachito para Asisbane -->
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValorDeb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValorCre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr >
        <td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td>
        <td class="colnum"><strong>TOTAL </strong></td>
        <?php if (( $this->_tpl_vars['sum']['det_ValorDeb'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorDeb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td>0.00</td>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['sum']['det_ValorCre'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorCre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td>0.00</td>
        <?php endif; ?>
    </tr>

  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>