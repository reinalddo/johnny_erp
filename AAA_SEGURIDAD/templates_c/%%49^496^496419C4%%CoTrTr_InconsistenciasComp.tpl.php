<?php /* Smarty version 2.6.18, created on 2011-06-20 11:59:12
         compiled from ../Co_Files/CoTrTr_InconsistenciasComp.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 22, false),array('block', 'report_header', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 23, false),array('block', 'report_detail', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 49, false),array('block', 'report_footer', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 66, false),array('modifier', 'number_format', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 55, false),array('modifier', 'date_format', '../Co_Files/CoTrTr_InconsistenciasComp.tpl', 73, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para reporte: Cuadro de Cuentas por Pagar
      Consolidado para todas las empresas
      Erika Suárez
      29/Ene/2010
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuentas por Pagar - Consolidado</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>
<?php $this->assign('countRec', 0); ?>
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>
 <br>
    </p>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
      </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">COMPROBANTES DESCUADRADOS</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
	<td class="headerrow"><strong>CREDITO</strong></td>
    	<td class="headerrow"><strong>SALDO<strong></td>
   </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['tip']; ?>
 </td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['cmp']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['reg']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['fecha']; ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['deb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['cre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	
	<?php $this->assign('tdeb', $this->_tpl_vars['tdeb']+$this->_tpl_vars['rec']['deb']); ?>
	<?php $this->assign('tcre', $this->_tpl_vars['tcre']+$this->_tpl_vars['rec']['cre']); ?>
	<?php $this->assign('tsal', $this->_tpl_vars['tsal']+$this->_tpl_vars['rec']['sal']); ?>
	<?php $this->assign('countRec', $this->_tpl_vars['countRec']+1); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Total Reg:</td><td><?php echo $this->_tpl_vars['countRec']; ?>
</td><td>Suman:</td>
       <td><?php echo $this->_tpl_vars['tdeb']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tcre']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tsal']; ?>
</td>
    </tr>
  <!-- <td colspan="14" style="text-align:left"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
  <td><?php echo $this->_tpl_vars['PiePagina']; ?>
</td> -->
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>




<?php $this->assign('countRec', 0); ?>
<!--  reporte de los comprobantes con estado pendiente -->
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData3'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<br><br>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
    </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">COMPROBANTES PENDIENTES</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
   </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['tip']; ?>
 </td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['cmp']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['reg']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['fecha']; ?>
</td>

	<?php $this->assign('countRec', $this->_tpl_vars['countRec']+1); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Totales:</td>
       <td><?php echo $this->_tpl_vars['countRec']; ?>
</td><td></td>
    </tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>






<?php $this->assign('countRec', 0); ?>
<!--  reporte de los comprobantes que tienen el periodo incorrecto-->
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData2'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<br><br>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="17"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">PERIODOS INCORRECTOS</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
        <td class="headerrow"><strong>PERIODO</strong></td>
	<td class="headerrow"><strong>PERIODO COMP</strong></td>
   </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['tip']; ?>
 </td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['cmp']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['reg']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['fecha']; ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['periodo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['periodoCmp'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	
	<?php $this->assign('countRec', $this->_tpl_vars['countRec']+1); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Totales:</td>
       <td><?php echo $this->_tpl_vars['countRec']; ?>
</td><td></td>
       <td></td><td></td>
    </tr>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>




</body>