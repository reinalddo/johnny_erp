<?php /* Smarty version 2.6.18, created on 2016-06-23 07:25:50
         compiled from CoTrTr_CXCDetallado.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_CXCDetallado.tpl', 19, false),array('block', 'report_header', 'CoTrTr_CXCDetallado.tpl', 21, false),array('block', 'report_detail', 'CoTrTr_CXCDetallado.tpl', 67, false),array('block', 'report_footer', 'CoTrTr_CXCDetallado.tpl', 83, false),array('modifier', 'date_format', 'CoTrTr_CXCDetallado.tpl', 33, false),array('modifier', 'number_format', 'CoTrTr_CXCDetallado.tpl', 73, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CXC</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<!-- resort=true -->
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "CCU, CAU, CHE")); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong>
	<br> <?php echo $this->_tpl_vars['subtitulo']; ?>

	<br> <?php echo $this->_tpl_vars['rec']['cue_Descripcion']; ?>

    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="16"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">COMPROBANTE</td>
        <td class="headerrow"><strong>No DOCUMENTO</strong></td>
	<td class="headerrow">FECHA FACTURA</td>
	<td class="headerrow"><strong>CONCEPTO</strong></td>
    	<td class="headerrow"><strong>DEBITO<strong></td>
	<td class="headerrow"><strong>CREDITO<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>	
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_header', array('group' => 'CCU')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr >
    <td colspan=7>
      	<b> CUENTA: </b>
	<?php echo $this->_tpl_vars['rec']['CCU']; ?>
 <?php echo $this->_tpl_vars['rec']['CUE']; ?>

    </td>
  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'CAU')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<tr >
    <td colspan=7>
      <b> CLIENTE: </b>
	<?php echo $this->_tpl_vars['rec']['CAU']; ?>
 <?php echo $this->_tpl_vars['rec']['DES']; ?>

    </td>
  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="text-align:center; vertical-align:middle;">
	<td class="coldata "> <?php echo $this->_tpl_vars['rec']['comp']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['CHE']; ?>
</td>
	<td class="coldata "> <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="coldata "align="left"><?php echo $this->_tpl_vars['rec']['com_Concepto']; ?>
</td>
	<td class="colnum " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValDebito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
  	<td class="colnum " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValCredito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td></td>
	<?php $this->assign('TDB', $this->_tpl_vars['TDB']+$this->_tpl_vars['rec']['det_ValDebito']); ?>
	<?php $this->assign('TCD', $this->_tpl_vars['TCD']+$this->_tpl_vars['rec']['det_ValCredito']); ?>
	<?php $this->assign('TSL', $this->_tpl_vars['TDB']-$this->_tpl_vars['TCD']); ?>
  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'CHE')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr >
      <td></td><td></td><td></td>
       <td><strong>Saldo de la factura<strong></td>
       <td></td><td></td>
       <td class="colnum "><strong><?php echo $this->_tpl_vars['rec']['SAL']; ?>
</strong></td>       
    </tr>
    <tr></tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td>
       <td> Totales: </td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TCD'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['TSL'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>       
    </tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>