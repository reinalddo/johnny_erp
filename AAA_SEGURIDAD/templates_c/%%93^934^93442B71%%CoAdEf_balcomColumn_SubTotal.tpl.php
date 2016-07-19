<?php /* Smarty version 2.6.18, created on 2015-03-26 11:51:45
         compiled from ../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 11, false),array('block', 'report_header', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 14, false),array('block', 'report_detail', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 93, false),array('block', 'report_footer', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 117, false),array('modifier', 'number_format', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 69, false),array('modifier', 'substr', '../Co_Files/CoAdEf_balcomColumn_SubTotal.tpl', 96, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />
<title><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://<?php echo $this->_tpl_vars['SERVER']; ?>
<?php echo $this->_tpl_vars['Path']; ?>
/../../css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://<?php echo $this->_tpl_vars['SERVER']; ?>
<?php echo $this->_tpl_vars['Path']; ?>
/../../css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://<?php echo $this->_tpl_vars['SERVER']; ?>
<?php echo $this->_tpl_vars['Path']; ?>
/../../css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false,'groups' => 'NIV')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
   
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=8 align="center" style="text-align: center; font-weight:bold; "><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=8 align="center" style="text-align: center; font-weight:bold; ">BALANCE DE COMPROBACION </td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=8 align="center" style="text-align: center; font-weight:bold; ">Acumulado a: <?php echo $this->_tpl_vars['rec']['PERI']; ?>
 </td>
			</tr>
	      		    
			<tr>
			<td  align="center">Cuenta</td>
			<td  align="center">Auxiliar</td>
			<td  align="center">Descripcion</td>
			<td  align="center">Saldo Anterior</td>
			<td  align="center">VDB</td>
			<td  align="center">VCR</td>
			<td  align="center">SAB</td>
			<td  align="center">SNT</td>

			</tr>
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'NIV')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<!-- Agrupar valores de activo y pasivo -->
    <?php if ($this->_tpl_vars['rec']['NIV'] == 1): ?>
			  <?php if ($this->_tpl_vars['rec']['CUE'] == 1): ?>
			    <?php $this->assign('TActSAN', $this->_tpl_vars['rec']['SAN']); ?>
			    <?php $this->assign('TActVDB', $this->_tpl_vars['rec']['VDB']); ?>
			    <?php $this->assign('TActVCR', $this->_tpl_vars['rec']['VCR']); ?>
			    <?php $this->assign('TActSAB', $this->_tpl_vars['rec']['SAB']); ?>
			    <?php $this->assign('TActSNT', $this->_tpl_vars['rec']['SNT']); ?>
			  <?php endif; ?>
			  <?php if ($this->_tpl_vars['rec']['CUE'] == 2): ?>
			    <?php $this->assign('TPasSAN', $this->_tpl_vars['rec']['SAN']); ?>
			    <?php $this->assign('TPasVDB', $this->_tpl_vars['rec']['VDB']); ?>
			    <?php $this->assign('TPasVCR', $this->_tpl_vars['rec']['VCR']); ?>
			    <?php $this->assign('TPasSAB', $this->_tpl_vars['rec']['SAB']); ?>
			    <?php $this->assign('TPasSNT', $this->_tpl_vars['rec']['SNT']); ?>
			  <?php endif; ?>
			  
			  <!-- Total de la cuenta anterior a la del grupo actual -->
			  <?php if (( $this->_tpl_vars['GrpCue'] != $this->_tpl_vars['rec']['CUE'] ) && ( $this->_tpl_vars['GrpCue'] != "" )): ?>
			    <tr style="white-space:nowrap; font-weight:bold;">
			      <td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			      <td align="Left">TOTAL DE <?php echo $this->_tpl_vars['GrpDES']; ?>
</td>
			      <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			      <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpVDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			      <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpVCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			      <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			      <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSNT'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			    </tr>
			  <?php endif; ?>
			  <?php $this->assign('GrpCue', $this->_tpl_vars['rec']['CUE']); ?>
			  <?php $this->assign('GrpDES', $this->_tpl_vars['rec']['DES']); ?>
			  
			  <?php $this->assign('GrpSAN', $this->_tpl_vars['rec']['SAN']); ?>
			  <?php $this->assign('GrpVDB', $this->_tpl_vars['rec']['VDB']); ?>
			  <?php $this->assign('GrpVCR', $this->_tpl_vars['rec']['VCR']); ?>
			  <?php $this->assign('GrpSAB', $this->_tpl_vars['rec']['SAB']); ?>
			  <?php $this->assign('GrpSNT', $this->_tpl_vars['rec']['SNT']); ?>
			  
			  <?php $this->assign('TSAN', $this->_tpl_vars['TSAN']+$this->_tpl_vars['rec']['SAN']); ?>
			  <?php $this->assign('TVDB', $this->_tpl_vars['TVDB']+$this->_tpl_vars['rec']['VDB']); ?>
			  <?php $this->assign('TVCR', $this->_tpl_vars['TVCR']+$this->_tpl_vars['rec']['VCR']); ?>
			  <?php $this->assign('TSAB', $this->_tpl_vars['TSAB']+$this->_tpl_vars['rec']['SAB']); ?>
			  <?php $this->assign('TSNT', $this->_tpl_vars['TSNT']+$this->_tpl_vars['rec']['SNT']); ?>
    <?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap;">
			
			<?php if (( ( ((is_array($_tmp=$this->_tpl_vars['rec']['CUE'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 1) : substr($_tmp, 0, 1)) ) == ' ' )): ?>
			  <td align="LEFT">&nbsp</td>
			<?php else: ?>
			  <td align="LEFT"><?php echo $this->_tpl_vars['rec']['CUE']; ?>
</td>
			<?php endif; ?>
			<?php if (( ( ((is_array($_tmp=$this->_tpl_vars['rec']['CUE'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 1) : substr($_tmp, 0, 1)) ) == ' ' )): ?>
			  <td align="LEFT"><?php echo $this->_tpl_vars['rec']['CUE']; ?>
</td>
			<?php else: ?>
			  <td align="LEFT">&nbsp</td>
			<?php endif; ?>
			
			<td align="Left"><?php echo $this->_tpl_vars['rec']['DES']; ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['SNT'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<!-- Total del ultimo grupo de cuentas -->
	<tr style="white-space:nowrap; font-weight:bold;">
			<td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			<td align="Left">TOTAL DE <?php echo $this->_tpl_vars['GrpDES']; ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpVDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpVCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['GrpSNT'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	</tr>
	 
	<!-- Resultado del Balance -->
	<tr style="white-space:nowrap; font-weight:bold;">
			<td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			<td align="Left">TOTALES</td> 
			
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['TSAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['TVDB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['TVCR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['TSAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['TSNT'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	</tr> 
	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>





</table>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>