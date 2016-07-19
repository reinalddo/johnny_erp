<?php /* Smarty version 2.6.18, created on 2009-12-04 11:53:49
         compiled from CoTrTr_Mayor.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_Mayor.tpl', 15, false),array('block', 'report_header', 'CoTrTr_Mayor.tpl', 16, false),array('block', 'report_detail', 'CoTrTr_Mayor.tpl', 46, false),array('block', 'report_footer', 'CoTrTr_Mayor.tpl', 69, false),array('modifier', 'date_format', 'CoTrTr_Mayor.tpl', 18, false),array('modifier', 'number_format', 'CoTrTr_Mayor.tpl', 56, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Mayor</title>
  
</head>
<body id="top" style="font-family:'Arial'; font-size:0.9em;">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "fecha,numero,det_secuencia",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Codigo</td>
            <td class="headerrow"><strong>Tipo</strong></td>
            <td class="headerrow"><strong>Numero</strong></td>
	    <td class="headerrow"><strong>Cod Cuenta</strong></td>
	    <td class="headerrow"><strong>Nom Cta</strong></td>
	    <td class="headerrow"><strong>Cod Aux</strong></td>
            <td class="headerrow"><strong>Nom Aux</strong></td>
            <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Descripcion</strong></td>
            <td class="headerrow"><strong>Debito</strong></td>
            <td class="headerrow"><strong>Credito</strong></td>
            <td class="headerrow"><strong>Saldo</strong></td>
            <td class="headerrow"><strong>Saldo F</strong></td>
            <!--<td class="headerrow"><strong>Act.</strong></td>-->
            <td class="headerrow"><strong>Cheque</strong></td>            
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td nowrap><?php echo $this->_tpl_vars['rec']['codigo']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['tipo']; ?>
</td>        
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['numero']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['codcuenta']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['cuenta']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['codauxiliar']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['nom_aux']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['fecha']; ?>
</td>
	<td class="coldata "><?php echo $this->_tpl_vars['rec']['descripcion']; ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['debito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['credito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['saldo']; ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['saldof'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <!--<td class="colnum "><?php echo $this->_tpl_vars['rec']['act']; ?>
</td>-->
	<?php if (( $this->_tpl_vars['rec']['cheque'] != 0 )): ?>
	  <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['cheque']; ?>
</td>
	<?php else: ?>
	  <td>&nbsp</td>
	<?php endif; ?>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td colspan=12>&nbsp</td></tr>
    <tr>
        <!--<td colspan=5>&nbsp</td>-->
        <td colspan=7 class="colnum"><strong>TOTALES:</strong></td>
        <td class="colnum headerrow" style="border:solid 2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['debito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum headerrow" style="border:solid 2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['credito'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<?php $this->assign('tot', $this->_tpl_vars['sum']['debito']-$this->_tpl_vars['sum']['credito']); ?>
        <td class="colnum headerrow" style="border:solid 2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['tot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum headerrow" style="border:solid 2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['saldof'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td>&nbsp</td>
    </tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>