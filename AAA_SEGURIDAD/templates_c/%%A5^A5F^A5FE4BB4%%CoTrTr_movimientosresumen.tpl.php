<?php /* Smarty version 2.6.18, created on 2010-03-11 11:48:46
         compiled from CoTrTr_movimientosresumen.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_movimientosresumen.tpl', 16, false),array('block', 'report_header', 'CoTrTr_movimientosresumen.tpl', 19, false),array('block', 'report_detail', 'CoTrTr_movimientosresumen.tpl', 62, false),array('block', 'report_footer', 'CoTrTr_movimientosresumen.tpl', 66, false),array('modifier', 'default', 'CoTrTr_movimientosresumen.tpl', 52, false),array('modifier', 'number_format', 'CoTrTr_movimientosresumen.tpl', 56, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Resumen de Movimientos de Cuentas</title>
  
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
        RESUMEN DE MOVIMIENTOS DE CUENTAS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    <table border=1 cellspacing=0 >
      <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">DESCRIPCION</td>
	<td class="headerrow"><strong>SALDO ANTERIOR</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
        <td class="headerrow"><strong>CREDITO</strong></td>
        <td class="headerrow"><strong>SALDO</strong></td>
      </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'det_CodCuenta')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr ><td><strong>CUENTA: </strong><?php echo $this->_tpl_vars['rec']['det_CodCuenta']; ?>
: <?php echo $this->_tpl_vars['rec']['txt_NombCuenta']; ?>
</td>
	  <td></td><td></td><td></td><td></td>
    </tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'det_CodAuxiliar')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="vertical-align:middle;"><!--height:40px; -->
      <td style="vertical-align:bottom;"><!--colspan=8 -->
	<!--<strong>AUXILIAR: </strong>--><?php echo $this->_tpl_vars['rec']['det_CodAuxiliar']; ?>
: <?php echo $this->_tpl_vars['rec']['txt_NombAuxiliar']; ?>

      </td>
      <?php $this->assign('cue', $this->_tpl_vars['rec']['det_CodCuenta']); ?>
      <?php $this->assign('aux', $this->_tpl_vars['rec']['det_CodAuxiliar']); ?>
      <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['cue']][$this->_tpl_vars['aux']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
      <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	<td class="colnum " style="text-align:right;"><?php echo 0; ?>
</td>
      <?php else: ?>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
      <?php endif; ?>
    <!--</tr>-->
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
   
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'det_CodAuxiliar')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    
        <?php if (( $this->_tpl_vars['sum']['det_ValorDeb'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorDeb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td></td>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['sum']['det_ValorCre'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorCre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td></td>
        <?php endif; ?>
        <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal']+$this->_tpl_vars['sum']['txt_Saldo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array('group' => 'det_CodCuenta')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<tr><td></td><td></td><td></td><td></td></tr>-->
    <tr >
        
        <td class="colnum"><strong>SUBTOTAL CUENTA </strong></td>
	<td></td>
        <?php if (( $this->_tpl_vars['sum']['det_ValorDeb'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorDeb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td></td>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['sum']['det_ValorCre'] > 0 )): ?>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValorCre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <?php else: ?>
            <td></td>
        <?php endif; ?>
        <td></td>
       
	
    </tr>
    <tr><td></td><td></td><td></td><td></td></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>