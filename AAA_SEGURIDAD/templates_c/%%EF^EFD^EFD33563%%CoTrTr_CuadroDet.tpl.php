<?php /* Smarty version 2.6.18, created on 2014-01-27 18:43:15
         compiled from CoTrTr_CuadroDet.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_CuadroDet.tpl', 18, false),array('block', 'report_header', 'CoTrTr_CuadroDet.tpl', 20, false),array('block', 'report_detail', 'CoTrTr_CuadroDet.tpl', 55, false),array('block', 'report_footer', 'CoTrTr_CuadroDet.tpl', 71, false),array('modifier', 'date_format', 'CoTrTr_CuadroDet.tpl', 31, false),array('modifier', 'number_format', 'CoTrTr_CuadroDet.tpl', 62, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="root" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Detalle de Cuenta por Pagar</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>
 <br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="14"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    <tr>
      <td><strong>PROVEEDOR DE FACTURA:</strong></td>
      <td colspan="6"><?php echo $this->_tpl_vars['rec']['det_idauxiliar']; ?>
 - 
      <?php echo $this->_tpl_vars['pnombreProveedor']; ?>
</td>
    </tr>
    <tr>
      <td><strong>FACTURA:</strong></td>
      <td colspan="6"><?php echo $this->_tpl_vars['rec']['det_numcheque']; ?>
</td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow"><?php echo $this->_tpl_vars['Encabezado']; ?>
</td>
	<td class="headerrow">CUENTA CONTABLE.</td>
	<td class="headerrow">TIPO DE COMPROBRANTE.</td>
	<td class="headerrow">No DE COMPROBANTE.</td>
	<td class="headerrow">FECHA CONTABLE</td>
	<td class="headerrow">VALOR.</td>
	<td class="headerrow">GLOSA.</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <tr style="text-align:center; vertical-align:middle;">
	<td class="colnum " align="center"><?php echo $this->_tpl_vars['rec']['empresa']; ?>
</td>
        <td class="colnum " align="center"><?php echo $this->_tpl_vars['rec']['det_codcuenta']; ?>
</td>
	<td class="colnum " align="center"><?php echo $this->_tpl_vars['rec']['com_tipocomp']; ?>
</td>
	<td class="colnum " align="center"><?php echo $this->_tpl_vars['rec']['com_NumComp']; ?>
</td>
	<td class="colnum " align="center"><?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="colnum " align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td align="center"><?php echo $this->_tpl_vars['rec']['det_Glosa']; ?>
</td>
  
  		<?php $this->assign('tvalor', $this->_tpl_vars['tvalor']+$this->_tpl_vars['rec']['valor']); ?>

    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
 
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td </td>
       <td </td>
       <td </td>
       <td </td>
       <td> Total: </td>
       <td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['tvalor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
       <td></td>
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
</body>