<?php /* Smarty version 2.6.18, created on 2009-04-02 16:36:02
         compiled from CoTrTr_confirmacion.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_confirmacion.tpl', 13, false),array('block', 'report_header', 'CoTrTr_confirmacion.tpl', 15, false),array('block', 'report_detail', 'CoTrTr_confirmacion.tpl', 42, false),array('block', 'report_footer', 'CoTrTr_confirmacion.tpl', 54, false),array('modifier', 'date_format', 'CoTrTr_confirmacion.tpl', 25, false),array('modifier', 'string_format', 'CoTrTr_confirmacion.tpl', 49, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="author" content="Fausto Astudillo" />
    <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONFIRMACION</title>
  </head>
  
  <body id="top" style="font-family:'Arial'; font-size:10;">
    <!--,txt_Nombre,cnf_Fecha,det_TipoComp,det_NumComp,det_Valor,c_total-->
    <?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => 'det_Banco','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    
    <?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <hr/>
        <!--<?php echo $this->_tpl_vars['rec']['gsEmpre']; ?>
-->
        <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:16; font-weight:bold"> PRUEBA</span>
        <div style="float:left;width:350px; font-size:0.9em;">
          <p style="text-align: center; display:block; width=60%">ENTREGA DE CHEQUES<br>
          FECHA DE ENTREGA: <?php echo $this->_tpl_vars['rec']['cnf_Fecha']; ?>
</p>
        </div>
        <p style="text-align: left; font:12; display:block; width=60%; font-size:0.8em;">
              No.: <strong><?php echo $this->_tpl_vars['rec']['cnf_NumReg']; ?>
</strong><br>
              Fecha de Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>

        </p>
        <br>
        <table border=1 cellspacing=0 >
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
    <?php $this->_tag_stack[] = array('report_header', array('group' => 'det_Banco')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <tr>
            <td class="colhead headerrow">BANCO</td>
            <td class="colhead headerrow">CHEQUE</td>
            <td class="colhead headerrow" colspan=2>EGRESO</td>
            <td class="colhead headerrow">PROVEEDOR</td>
            <td class="colhead headerrow">VALOR</td>
        </tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
        
    <?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
            <tr>
                <td nowrap class="coldat"><?php echo $this->_tpl_vars['rec']['det_Banco']; ?>
</td>
                <td class="coldat"><?php echo $this->_tpl_vars['rec']['det_Cheque']; ?>
</td>
                <td class="coldat"><?php echo $this->_tpl_vars['rec']['det_TipoComp']; ?>
</td>
                <td class="coldat colnum "><?php echo $this->_tpl_vars['rec']['det_NumComp']; ?>
</td>
                <td class="coldat " style="width:200px;"><?php echo $this->_tpl_vars['rec']['txt_Nombre']; ?>
</td>
                <td class="coldat colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_Valor'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
            </tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> 
        
    
    <?php $this->_tag_stack[] = array('report_footer', array('group' => 'det_Banco')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <tr>
            <td class="colnum " colspan=5>TOTAL </td>
            <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_Valor'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
</td>
        </tr>
        <tr></tr>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
    <?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        </table>
        <p>&nbsp</p>
        <table border=0 cellspacing=0 width=50%>
          <tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>
          <tr>
            <td class="coldat" style="width:150px;"><p style="border-top:solid;">ELABORADO POR</p></td>
            <td>&nbsp</td>
            <td class="coldat" style="width:150px;"><p style="border-top:solid;">AUTORIZADO POR</p></td>
            <td>&nbsp</td>
            <td class="coldat" style="width:180px;"><p style="border-top:solid;">RECIBI CONFORME</p></td>
          </tr>
        </table>
        <hr/>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
  </body>