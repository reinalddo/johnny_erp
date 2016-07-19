<?php
class clsCabecera { //Cabecera class @1-841A2739

//Variables @1-1987AB94
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $TemplatePath;
    var $Visible;
//End Variables

//Class_Initialize Event @1-1D33D08B
    function clsCabecera($path='')
    {
        $this->TemplatePath = $path;
        $this->Visible = true;
        $this->FileName = "Cabecera.php";
        $this->Redirect = "";
        $this->TemplateFileName = "Cabecera.html";
        $this->BlockToParse = "main";
        $this->TemplateEncoding = "";
        if($this->Visible)
        {

            // Create Components
            $this->lbUsuario = new clsControl(ccsLabel, "lbUsuario", "lbUsuario", ccsText, "", CCGetRequestParam("lbUsuario", ccsGet));
            $this->lbEmpresa = new clsControl(ccsLabel, "lbEmpresa", "lbEmpresa", ccsText, "", CCGetRequestParam("lbEmpresa", ccsGet));
            $this->lbFecha = new clsControl(ccsLabel, "lbFecha", "lbFecha", ccsText, "", CCGetRequestParam("lbFecha", ccsGet));
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-E970CE0D
    function BindEvents()
    {
        $this->CCSEvents["BeforeShow"] = "Cabecera_BeforeShow";
        $this->CCSEvents["AfterInitialize"] = "Cabecera_AfterInitialize";
        $this->CCSEvents["OnInitializeView"] = "Cabecera_OnInitializeView";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-7E2A14CF
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
    }
//End Operations Method

//Initialize Method @1-EDD74DD5
    function Initialize()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
        if(!$this->Visible)
            return "";
    }
//End Initialize Method

//Show Method @1-D4C62366
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name, $this->TemplateEncoding);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->block_path = $Tpl->block_path . "/" . $Name;
        $this->lbUsuario->Show();
        $this->lbEmpresa->Show();
        $this->lbFecha->Show();
        $Tpl->Parse();
        $Tpl->block_path = $block_path;
        $Tpl->SetVar($Name, $Tpl->GetVar($Name));
    }
//End Show Method

} //End Cabecera Class @1-FCB6E20C

//Include Event File @1-91211ABF
include(RelativePath . "/De_Files/Cabecera_events.php");
//End Include Event File


?>
