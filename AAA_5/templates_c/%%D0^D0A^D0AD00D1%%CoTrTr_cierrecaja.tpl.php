<?php /* Smarty version 2.6.18, created on 2010-09-29 14:57:23
         compiled from CoTrTr_cierrecaja.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_cierrecaja.tpl', 21, false),array('block', 'report_header', 'CoTrTr_cierrecaja.tpl', 24, false),array('block', 'report_detail', 'CoTrTr_cierrecaja.tpl', 60, false),array('block', 'report_footer', 'CoTrTr_cierrecaja.tpl', 156, false),array('modifier', 'default', 'CoTrTr_cierrecaja.tpl', 64, false),array('modifier', 'number_format', 'CoTrTr_cierrecaja.tpl', 68, false),array('modifier', 'date_format', 'CoTrTr_cierrecaja.tpl', 249, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .tot{background-color:#D9D9D9;}
    </style>
  '; ?>

  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CIERRE DE CAJA</title>
  
</head>

<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "orden,txt",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->assign('acum', 0); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        CIERRE DE CAJA<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">CAJA/BANCOS</td>
            <td class="headerrow"><strong>SDO/CAJA DEL DIA</strong></td>
            <td class="headerrow"><strong>SDO/INI LIBROS</strong></td>
            <td class="headerrow"><strong>(+) DEP.</strong></td>
            <td class="headerrow"><strong>(+) N/C</strong></td>
	    
	    <td class="headerrow"><strong>CH. N/A.</strong></td>
	    
	    <!-- <td class="headerrow"><strong>CH. CONFIRMADOS.</strong></td>
	    <td class="headerrow"><strong>CH. PAGADOS.</strong></td>-->
	    
            <td class="headerrow"><strong>CH. EMITIDOS CUSTODIA.</strong></td>
            <td class="headerrow"><strong>(-) CHEQUES GIRADOS</strong></td>
            <td class="headerrow"><strong>N/D</strong></td>
            <td class="headerrow"><strong>SDO/FIN LIBRO BANCOS</strong></td>
            <td class="headerrow"><strong>SDO DISP. CAJA-BANCOS</strong></td>
        </tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td><?php echo $this->_tpl_vars['rec']['cod']; ?>
 - <?php echo $this->_tpl_vars['rec']['txt']; ?>
</td>
        <?php $this->assign('aux', $this->_tpl_vars['rec']['cod']); ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['caja'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['ant'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['dep'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['nc'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['NA'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  <!-- 
	  
	  <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['CF'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  
	  <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['PG'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  -->
	  
	  
	  
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['emit'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  
	  
	  
	  
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['cheque'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	     <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td> 
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['nd'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['final'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><strong><?php echo 0; ?>
</strong></td>
	  <?php else: ?>
	    <td class="colnum "><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</strong></td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['totfin'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum "><strong><?php echo 0; ?>
</strong></td>
	  <?php else: ?>
	    <td class="colnum "><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</strong></td>
	  <?php endif; ?>
    </tr>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;">
        <td style="font-style:italic;">Total</td>
        <!--<td></td>
        <td></td>-->
        <?php $this->assign('aux', -1); ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['caja'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['ant'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['dep'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['nc'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  
	  
	  
	  <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['NA'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  <!-- 
	   <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['CF'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  
	   <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['PG'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
	  
	  -->
	  	  
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['anul'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['cheque'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['nd'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['final'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
        <?php $this->assign('sal', ((is_array($_tmp=@$this->_tpl_vars['agSaldos'][$this->_tpl_vars['aux']]['totfin'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))); ?>
	  <?php if (( $this->_tpl_vars['sal'] == 0 )): ?>
	    <td class="colnum tot"><?php echo 0; ?>
</td>
	  <?php else: ?>
	    <td class="colnum tot"><?php echo ((is_array($_tmp=$this->_tpl_vars['sal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <?php endif; ?>
    </tr>
    </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong><?php echo $_SESSION['g_user']; ?>
</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</p>
        <p style="line-height:0.5em;"><?php echo $this->_tpl_vars['agArchivo']; ?>
</p>
    </div>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>-->
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>