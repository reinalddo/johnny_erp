<?php

// //Events @1-F81417CB

//CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow @29-5073B4A6
function CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow()
{
    $CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow = true;
//End CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow

//Retrieve number of records @30-8BE9CC94
    global $CoAdAu_varimant;
    $CoAdAu_varimant->variables_list->genvariables_genvarconfig_TotalRecords->SetValue($CoAdAu_varimant->variables_list->ds->RecordsCount);
//End Retrieve number of records

//Close CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow @29-25B4F310
    return $CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow;
}
//End Close CoAdAu_varimant_variables_list_genvariables_genvarconfig_TotalRecords_BeforeShow
?>
