<?php

class clsLiEmTj_condemb { //LiEmTj_condemb class @1-47E978BF

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

//Class_Initialize Event @1-5A72DE93
    function clsLiEmTj_condemb()
    {
        $this->Visible = true;
        if($this->Visible)
        {
            $this->FileName = "LiEmTj_condemb.php";
            $this->Redirect = "";
            $this->TemplateFileName = "LiEmTj_condemb.html";
            $this->BlockToParse = "main";
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-236CCD5D
    function BindEvents()
    {
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

//Show Method @1-5D780256
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name);
        $Tpl->block_path = $Name;
        $Tpl->Parse();
        $Tpl->SetVar($Name, $Tpl->GetVar());
        $Tpl->block_path = $block_path;
    }
//End Show Method

} //End LiEmTj_condemb Class @1-FCB6E20C
?>
