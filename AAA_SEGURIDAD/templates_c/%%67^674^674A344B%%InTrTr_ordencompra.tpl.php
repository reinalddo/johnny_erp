<?php /* Smarty version 2.6.18, created on 2015-09-20 17:16:14
         compiled from InTrTr_ordencompra.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_ordencompra.tpl', 42, false),array('block', 'report_header', 'InTrTr_ordencompra.tpl', 43, false),array('block', 'report_detail', 'InTrTr_ordencompra.tpl', 108, false),array('block', 'report_footer', 'InTrTr_ordencompra.tpl', 126, false),array('modifier', 'number_format', 'InTrTr_ordencompra.tpl', 114, false),array('modifier', 'date_format', 'InTrTr_ordencompra.tpl', 186, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Johnny Valencia" />
  <?php echo '
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
      <style type="text/css">
        body{font-size:9px;}
        
        .padding10{padding:10px;}
        .empresa{height:30px;font-size:12px;font-weight:bold;text-align:center;vertical-align:middle;}
        .direccion{height:30px;font-size:11px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #000000 solid 1px;}
        .bordeDer {border-right: #000000 solid 1px;}
        .bordeIzq {border-left: #000000 solid 1px;}
        .bordeSup {border-top: #000000 solid 1px;}
        .bordeInf {border-bottom: #000000 solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        td {
        font-size: 10px;
    }
	
	@media screen{#exportar{display:block !important;}}

    </style>
  '; ?>

  
    <title>Orden de Compra</title>
   
</head>
<body id="top" style="font-family:'Arial'" onload="window.print();">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "com_NumComp,det_Secuencia",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <div id="exportar" style="display:none;">
      <a href="InTrTr_ordencompra.rpt.php?pQryCom=<?php echo $this->_tpl_vars['query']; ?>
&pExcel=1">Exportar a Excel</a>
    </div>
    <hr/>
    <?php $this->assign('numCol', 8); ?>
    
    
        <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        <tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 class="empresa bordeCompleto">
                <?php echo $_SESSION['g_empr']; ?>
 - <?php echo $this->_tpl_vars['subtitulo']; ?>
 </td>
        </tr>
        <tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 class="direccion bordeCompleto">
                <?php echo $this->_tpl_vars['direccion']; ?>
 </td>
        </tr>
        <tr>
            <td colspan=4 class="padding10 bordeCompleto"><strong>ORDEN DE COMPRA N°:</strong>   <?php echo $this->_tpl_vars['rec']['com_NumComp']; ?>
</td>
            <td colspan=1 class="padding10 bordeCompleto"><strong>EMISION:</strong>   <?php echo $this->_tpl_vars['rec']['com_FecTrans']; ?>
</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>SEMANA:</strong>   <?php echo $this->_tpl_vars['rec']['semana']; ?>
</td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>PROVEEDOR:</strong>   <?php echo $this->_tpl_vars['rec']['com_Receptor']; ?>
</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>RUC:</strong>   <?php echo $this->_tpl_vars['rec']['ruc']; ?>
</td>
        </tr>
        <tr>
            <td colspan=8 class="padding10 bordeCompleto"><strong>FORMA DE PAGO:</strong>
                <?php if (( $this->_tpl_vars['rec']['credito'] > 0 )): ?>
                    Credito <?php echo $this->_tpl_vars['rec']['credito']; ?>
 dias
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>USO:</strong><br>   <?php echo $this->_tpl_vars['rec']['com_Concepto']; ?>
</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>FECHA DE ENTREGA:</strong>   <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>SOLICITA:</strong>    </td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>TIPO DE FORMA:</strong>   </td>
        </tr>
        <tr style="height:40px;">
            <td colspan=8 class="padding10 bordeCompleto"><strong>CON CARGO A NUESTRA CUENTA SIRVASE DESPACHAR LO SIGUIENTE DE ACUERDO A COTIZACION PACTADA CON:</strong></td>
        </tr>
        
	<tr class="titulo bordeCompleto">
	  <td class=" bordeCompleto" style="width:4%;"><strong>Secuencia</strong></td>
	  <td class=" bordeCompleto" style="width:5%;"><strong>Codigo</strong></td>
          <td class=" bordeCompleto" style="width:50%;"><strong>Descripcion</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Unidad de Medida</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Cantidad</strong></td>	  
          <td class=" bordeCompleto" style="width:1%;"><strong>IVA</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Precio Unitario</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Importe Total</strong></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">headerrow-->
        <td class="der bordeIzq bordeDer" ><?php echo $this->_tpl_vars['rec']['det_Secuencia']; ?>
</td>  
	<td nowrap class="der bordeDer"><?php echo $this->_tpl_vars['rec']['cod']; ?>
</td>
        <td class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['descripcion']; ?>
</td>
        <td class="izq bordeDer"><?php echo $this->_tpl_vars['rec']['uni_Abreviatura']; ?>
</td>        
        <td class="der bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_CanDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
        
        <td class="cen bordeDer">
            <?php if (( 2 == $this->_tpl_vars['rec']['iva'] )): ?>
                *
            <?php endif; ?>
        </td>
	<td class="der bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['ValUnitario'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 6) : smarty_modifier_number_format($_tmp, 6)); ?>
</td>
	<td class="der bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <?php $this->assign('cuenta', $this->_tpl_vars['count']['det_CanDespachada']); ?>
    <?php $_from = $this->_tpl_vars['agFilas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
        <?php if (( $this->_tpl_vars['value'] > $this->_tpl_vars['cuenta'] )): ?>
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    
    <tr>
        <td class="der bordeIzq bordeDer bordeInf" ></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
    </tr>
    <tr class="bordeSup">
        <td colspan=5 rowspan=4 class="bordeInf bordeIzq"><strong>SON:</strong>  <?php echo $this->_tpl_vars['letras']; ?>
</td>
        <td colspan=2><strong>SUBTOTAL BASE 0%:</strong></td>
        <td class="der bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['iva0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td colspan=2><strong>SUBTOTAL BASE IVA:</strong></td>
        <td class="der bordeInf bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['iva12'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td colspan=2><strong>IVA:</strong></td>
        <td class="der bordeInf bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['iva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td colspan=2 class="bordeInf"><strong>TOTAL A PAGAR:</strong></td>
        <td class="der bordeInf bordeDer"><?php echo ((is_array($_tmp=$this->_tpl_vars['valorTot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td colspan=8 class="parrafo padding10 bordeCompleto"><strong>NOTA: </strong>FAVOR FACTURAR ORIGINAL Y DOS COPIAS, E INCLUIR
                UNA COPIA DE ESTA ORDEN DE COMPRA PARA AGILITAR PAGO. TODOS LOS PAQUETES, FACTURAS,
                DOCUMENTOS DE EMBARQUE Y CORRESPONDENCIA DEBEN CONTENER EL NUMERO DE ESTA ORDEN DE COMPRA.
        </td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=4 class="bordeCompleto"><strong>ELABORADO</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>REVISADO</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>APROBADO</strong></td>
    </tr>
    <tr style="text-align:center; height:60px;">
        <td colspan=4 class="bordeCompleto"><?php echo $this->_tpl_vars['rec']['com_Usuario']; ?>
, <?php echo $_SESSION['g_user']; ?>

                        <p><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</p>
        </td>
        <td colspan=2 class="bordeCompleto"></td>
        <td colspan=2 class="bordeCompleto"></td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=4 class="bordeCompleto"><strong>COMPRAS</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>SOLICITANTE/ADMIN HCDA</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>GERENCIA</strong></td>
    </tr>
    </table>
    </br>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>