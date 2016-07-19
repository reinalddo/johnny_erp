<?php /* Smarty version 2.6.18, created on 2010-09-07 10:42:10
         compiled from BiTrTr_bitacoraUbicacion.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'BiTrTr_bitacoraUbicacion.tpl', 14, false),array('block', 'report_header', 'BiTrTr_bitacoraUbicacion.tpl', 15, false),array('block', 'report_detail', 'BiTrTr_bitacoraUbicacion.tpl', 51, false),array('block', 'report_footer', 'BiTrTr_bitacoraUbicacion.tpl', 73, false),array('modifier', 'date_format', 'BiTrTr_bitacoraUbicacion.tpl', 17, false),array('modifier', 'number_format', 'BiTrTr_bitacoraUbicacion.tpl', 56, false),array('modifier', 'default', 'BiTrTr_bitacoraUbicacion.tpl', 56, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de ubicación de documentos en bitacora-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Bitacora - Ubicacion de Documentos</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'bit_codEmpresa','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <!-- <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br> -->
	<strong>BITACORA DE DOCUMENTOS</strong><br> 
        REPORTE DE UBICACION<br>
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow"><strong>Tipo de Documento</strong></td>
            <td class="headerrow"><strong>Documento</strong></td>
            <td class="headerrow"><strong>Proveedor</strong></td>
            <td class="headerrow"><strong>Emision</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
            <td class="headerrow"><strong>Usuario Actual</strong></td>
	    <td class="headerrow"><strong>Fecha Envio</strong></td>
            <td class="headerrow"><strong>Hora Envio</strong></td>
	    <td class="headerrow"><strong>Obs. Envio</strong></td>
	    <td class="headerrow"><strong>Usuario Envio</strong></td>
	    <td class="headerrow"><strong>Fecha Recepcion</strong></td>
	    <td class="headerrow"><strong>Hora Recepcion</strong></td>
	    <td class="headerrow"><strong>Obs. Recibe</strong></td>
	    <td class="headerrow"><strong>Estado</strong></td>
	    <td class="headerrow"><strong>Movimiento</strong></td>
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'bit_codEmpresa')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
	  <td colspan=15 nowrap><strong>EMPRESA:<?php echo $this->_tpl_vars['rec']['bit_empresa']; ?>
</strong></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td class="colnum"><?php echo $this->_tpl_vars['rec']['bit_tipoDoc']; ?>
</td>
        <td class="colnum"><?php echo $this->_tpl_vars['rec']['bit_secDoc']; ?>
-<?php echo $this->_tpl_vars['rec']['bit_emiDoc']; ?>
-<?php echo $this->_tpl_vars['rec']['bit_numDoc']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['proveedor']; ?>
</td>
        <td class="coldata" style="text-align:center;" nowrap><?php echo $this->_tpl_vars['rec']['bit_fechaDoc']; ?>
</td>
	<td class="colnum" style="text-align:right;" nowrap><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rec']['bit_valor'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	<td class="coldata" style="text-align:center; color:blue;"><?php echo $this->_tpl_vars['rec']['bit_usuarioActual']; ?>
</td>
	<td class="coldata" style="text-align:center;" nowrap><?php echo $this->_tpl_vars['rec']['FechaEnv']; ?>
</td>
	<td class="coldata" style="text-align:center;" nowrap><?php echo $this->_tpl_vars['rec']['HoraEnv']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['bit_observacionenvio']; ?>
</td>
	<td class="coldata" style="text-align:center;"><?php echo $this->_tpl_vars['rec']['usrEnvia']; ?>
</td>
	<td class="coldata" style="text-align:center;" nowrap><?php echo $this->_tpl_vars['rec']['FechaRec']; ?>
</td>
	<td class="coldata" style="text-align:center;" nowrap><?php echo $this->_tpl_vars['rec']['HoraRec']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['bit_observacion']; ?>
</td>
	
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['estado']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['movimiento']; ?>
</td>
	
        
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    
</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>