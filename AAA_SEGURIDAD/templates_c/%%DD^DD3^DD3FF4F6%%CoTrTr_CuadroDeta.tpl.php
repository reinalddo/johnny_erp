<?php /* Smarty version 2.6.18, created on 2014-01-24 15:06:17
         compiled from CoTrTr_CuadroDeta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'str_pad', 'CoTrTr_CuadroDeta.tpl', 2, false),array('modifier', 'date_format', 'CoTrTr_CuadroDeta.tpl', 34, false),array('modifier', 'number_format', 'CoTrTr_CuadroDeta.tpl', 77, false),array('block', 'report', 'CoTrTr_CuadroDeta.tpl', 21, false),array('block', 'report_header', 'CoTrTr_CuadroDeta.tpl', 23, false),array('block', 'report_detail', 'CoTrTr_CuadroDeta.tpl', 59, false),array('block', 'report_footer', 'CoTrTr_CuadroDeta.tpl', 92, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  	    &nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['establecimiento'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmision'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secuencial'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 7, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 7, '0', 'STR_PAD_LEFT')); ?>
  -->
<!--  	    <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['estabretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmiRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 3, '0', 'STR_PAD_LEFT')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 7, '0', 'STR_PAD_LEFT') : str_pad($_tmp, 7, '0', 'STR_PAD_LEFT')); ?>
</td>  -->
<!--  	    Plantilla para reporte de Cuadro de Cuentas por Pagar -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="root" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuadro</title>
  
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
	<td colspan="17"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">FECHA CONTABL.</td>
	<td class="headerrow"><strong>No COMPROBANTE</strong></td>
        <td class="headerrow"><strong>CODIGO</strong></td>
	<td class="headerrow"><strong>PROVEEDOR</strong></td>
    	<td class="headerrow"><strong>FACTURA<strong></td>
	<td class="headerrow"><strong>No RETENCION</strong></td>
        <td class="headerrow"><strong>FECHA EMISION</strong></td>
	<td class="headerrow"><strong>FECHA VENCIMIENTO</strong></td>
        <td class="headerrow"><strong>DIAS VENCIDOS</strong></td>
        <td class="headerrow"><strong>CONCEPTO</strong></td>
        <td class="headerrow"><strong>VALOR FACTURA</strong></td>
        <td class="headerrow"><strong>IVA</strong></td>
	<td class="headerrow"><strong>TOTAL</strong></td>
	<td class="headerrow"><strong>ESTADO</strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>USUARIO/FECHA DE DIGITACION</strong></td>
	<td class="headerrow"><strong>INFORMACION DEL PAGO</strong></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

  <tr style="text-align:center; vertical-align:middle;">
      <td class="colnum "> <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['numComprobante']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['idProvFact']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['nombreProveedor']; ?>
</td>
	<td class="colnum " >
	    <?php if ($this->_tpl_vars['rec']['ID']): ?>
	    <a href= "../Co_Files/CoTrTr_CuadroDet.rpt.php?&pidProvFact=<?php echo $this->_tpl_vars['rec']['idProvFact']; ?>
&pnombreProveedor=<?php echo $this->_tpl_vars['rec']['nombreProveedor']; ?>
&psecuencial=<?php echo $this->_tpl_vars['rec']['secuencial']; ?>
" target="_blank">
	    <?php endif; ?>
	    &nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['establecimiento'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmision'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secuencial'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 9, '0', @STR_PAD_LEFT) : str_pad($_tmp, 9, '0', @STR_PAD_LEFT)); ?>

	</td>	
	<td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['estabretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmiRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secretencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 9, '0', @STR_PAD_LEFT) : str_pad($_tmp, 9, '0', @STR_PAD_LEFT)); ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['fechaEmision']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['com_FecVencim']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['diasVencidos']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['concepto']; ?>
</td>
	<td class="colnum " ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valFactura'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['iva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['total'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['estado']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['cpp_saldo']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['usuario']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['detalle']; ?>
</td>
	
  	<?php $this->assign('tValor', $this->_tpl_vars['tValor']+$this->_tpl_vars['rec']['valFactura']); ?>
	<?php $this->assign('tIva', $this->_tpl_vars['tIva']+$this->_tpl_vars['rec']['iva']); ?>
	<?php $this->assign('tTotal', $this->_tpl_vars['tTotal']+$this->_tpl_vars['rec']['total']); ?>
	<?php $this->assign('tSaldo', $this->_tpl_vars['tSaldo']+$this->_tpl_vars['rec']['cpp_saldo']); ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
      <td></td><td></td>
       <td> Totales: </td>
       <td><?php echo $this->_tpl_vars['tValor']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tIva']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tTotal']; ?>
</td>
       <td></td>
       <td><?php echo $this->_tpl_vars['tSaldo']; ?>
</td>
       <td></td><td></td>
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