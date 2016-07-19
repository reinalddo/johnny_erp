<?php /* Smarty version 2.6.18, created on 2011-02-28 15:11:30
         compiled from CoTrTr_movimientosimple.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_movimientosimple.tpl', 19, false),array('block', 'report_header', 'CoTrTr_movimientosimple.tpl', 22, false),array('block', 'report_detail', 'CoTrTr_movimientosimple.tpl', 79, false),array('block', 'report_footer', 'CoTrTr_movimientosimple.tpl', 129, false),array('modifier', 'default', 'CoTrTr_movimientosimple.tpl', 86, false),array('modifier', 'number_format', 'CoTrTr_movimientosimple.tpl', 90, false),array('modifier', 'date_format', 'CoTrTr_movimientosimple.tpl', 130, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas - Simple</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>  
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        MOVIMIENTOS DE CUENTAS - SIMPLE<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan=4><?php echo $this->_tpl_vars['SMARTY']['script']; ?>
	  </td>
	<td colspan=4><?php echo $this->_tpl_vars['slPiePag']; ?>
</td>
      </tr>
    </tfoot>
    
    <tbody>

    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">COD. CUENTA</td>
	<td class="headerrow"><strong>NOMBRE DE CUENTA</strong></td>
        <td class="headerrow"><strong>COD. AUXILIAR</strong></td>
	<td class="headerrow"><strong>NOMBRE AUXILIAR</strong></td>
    	<td class="headerrow">COMP</td>
	<td class="headerrow"><strong>CHEQ</strong></td>
        <td class="headerrow"><strong>FECHA</strong></td>
	<td class="headerrow"><strong>COD. BENEFICIARIO</strong></td>
	<td class="headerrow"><strong>BENEFICIARIO</strong></td>
        <td class="headerrow"><strong>GLOSA</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
        <td class="headerrow"><strong>CREDITO</strong></td>
        <td class="headerrow"><strong>SALDO</strong></td>
    </tr>
    
    
	  

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['cue'] != $this->_tpl_vars['rec']['det_CodCuenta'] || $this->_tpl_vars['aux'] != $this->_tpl_vars['rec']['det_CodAuxiliar']): ?>
  <?php $this->assign('sal', 0); ?>
  <tr>
	  <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	  <td class="coldata ">Saldo Anterior</td>
	  <td></td><td></td>
	    <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['rec']['det_CodCuenta']][$this->_tpl_vars['rec']['det_CodAuxiliar']])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	    <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	      <td class="colnum "><?php echo 0; ?>
</td>
	    <?php else: ?>
	      <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	    <?php endif; ?>
  </tr>
<?php endif; ?>

  <tr>
      <td class="colnum "><?php echo $this->_tpl_vars['rec']['det_CodCuenta']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['txt_NombCuenta']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['det_CodAuxiliar']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['txt_NombAuxiliar']; ?>
</td>
	<td nowrap><?php echo $this->_tpl_vars['rec']['txt_Compr']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['com_Cheque']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['CRE']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['beneficiario']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['com_Concepto']; ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValorDeb'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValorCre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
				<td class="colnum ">
	 
	<?php $this->assign('sal', $this->_tpl_vars['sal']+$this->_tpl_vars['rec']['txt_Saldo']); ?>
	<?php echo $this->_tpl_vars['sal']; ?>

	
		
	<?php $this->assign('cue', $this->_tpl_vars['rec']['det_CodCuenta']); ?>
	<?php $this->assign('aux', $this->_tpl_vars['rec']['det_CodAuxiliar']); ?>
	
	  
		</td>
	<!--<td class="colnum "><?php echo $this->_tpl_vars['acum']; ?>
:<?php echo $this->_tpl_vars['rec']['txt_Saldo']; ?>
:<?php echo $this->_tpl_vars['sal']; ?>
</td>--><!--|number_format:0-->
		    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<td colspan="12" style="text-align:left"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
			<td ><?php echo $this->_tpl_vars['PiePagina']; ?>
</td>
  </tbody>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>