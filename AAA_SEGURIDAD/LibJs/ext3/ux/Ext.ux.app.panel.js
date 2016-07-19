/**
 *   Modificacion del panel, para soportar "items" un arrego con elementos definidos
 *
 * @rev fah  22/03/2011   Mensaje de inconsistencia en className al generar el Store
 */
function getThisObj(pDescr, olTmp){
    if (!olTmp) var olTmp = window;
    Ext.each(pDescr.split("."), function (pEl){
        olTmp = olTmp[pEl]
    })
    return olTmp
}
Ext.ns("Ext.ux.app");
Ext.ux.app.panel = function(pConfig){
    this.initialConfig=pConfig
    this.combos=[];
    if (!this.initialConfig.targetCmp)  this.initialConfig.targetCmp = tabs_c
        /**
     *
     *
     */
    this.configItem = function(itemCfg, pIdx){
        var fnAfterConfig = function(){return true};
        if (typeof itemCfg.fnAfterConfig== "function")  //            After Config element
            fnAfterConfig= itemCfg.fnAfterConfig.createDelegate(olItem, [itemCfg, ilIdx])

        if(!itemCfg.id)  itemCfg.id = this.initialConfig.idBase || this.initialConfig.id + "-"+ pIdx
        //var olItem = new Ext.Panel(itemCfg);
        this.items.push(itemCfg);   // olItem
        fnAfterConfig.createDelegate(this, [olItem]);
        fnAfterConfig();
    } //eof ConfigItemt()

    /**
     *  Function called after the required direcApi has been loaded
     *
     */
    this.afterDirectApi = function(){
        var slNamespace = this.initialConfig.namespace ;
        if (this.initialConfig.apiDescriptor) { // Si se cargo una Api especifica para el elemento // Compatibility with early versions
            var slApiDescr = this.initialConfig.namespace + "." + this.initialConfig.apiDescriptor;
            var olDprovider = eval("(" + slApiDescr + ")"); //getThisObj(slApiDescr)
            Ext.Direct.addProvider(olDprovider);
        }

        var ilIdx = 0
        var items=[];
        Ext.each(this.initialConfig.items, function(itemCfg){
            if (itemCfg.store && itemCfg.store.className) {             // Compatibility with early versions
                var olProvider = eval("(" + slNamespace + "." + itemCfg.store.className + ")")
                if (!olProvider) alert("DEFINICION EQUIVOCADA DE CONECTOR DIRECT PARA " + itemCfg.id + " (" + itemCfg.store.className + ")");
                itemCfg.store.api = {
                    create:     olProvider.create
                    ,read:      olProvider.getList
                    ,update:    olProvider.update
                    ,destroy:   olProvider.destroy}
            }
            else   {                             //#fah  22/03/2011
                if(itemCfg.store && itemCfg.store.xtype && itemCfg.store.xtype == "directstore"
                   && undefined == itemCfg.store.api
                   && undefined == itemCfg.store.apiProvider){
                    console.dir(itemCfg)
                    alert("SE REQUIERE DEFINIR LA PROPIEDAD  'className' o 'apiProvider' EN EL ORIGEN DE DATOS " + itemCfg.title + " (" + itemCfg.store.className + ")");
                }
            }
            var fnBeforeConfig = function(){return true};
            if (typeof itemCfg.beforeConfig == "function")  //            Before Coonfig
                fnBeforeConfig = itemCfg.beforeConfig.createDelegate(this, [ilIdx])
            if (!fnBeforeConfig()) return false;

            var fnAfterConfig = function(){return true};
            if (typeof itemCfg.fnAfterConfig== "function")  //            After Config element
                fnAfterConfig= itemCfg.fnAfterConfig.createDelegate(olItem, [itemCfg, ilIdx])

            if(!itemCfg.id)  itemCfg.id = (this.initialConfig.idBase || this.initialConfig.id )+ "-"+ ilIdx

            var olItem = itemCfg;  //new Ext.Component(itemCfg);

            items.push(olItem);
            fnAfterConfig.createDelegate(this, [olItem]);
            fnAfterConfig();
            ilIdx++;
        }, this)
        this.items = items;
        this.show();
    } // eof afterDirectApi method

    this.getItem = (function(pIdx){
        return this.items[pIdx];
    }).createDelegate(this)  //eof getItem
    /*
     *  @method virtual beforeShow. Must be overiden on final object
     */
    this.beforeShow = (function(){return true}).createDelegate(this)  //eof show
    /*
     *  @method virtual afterShow. Must be overiden on final object
     */
    this.afterShow = (function(){
        return true}).createDelegate(this)  //eof show
    /*
     *  @method show. CAn be overiden on final object. CReates a panel that renders on targetObjects (defaults tabs_c)
     *  This panel is a contaner for all items defined at module configuration
     */
    this.show = (function(){
        var olPnl= new Ext.Panel({ // @TODO: Probar scope de esta variable
            title:          this.initialConfig.title || "Modulo nuevo"
            ,tabTip :       this.initialConfig.tabTip || "**"
            ,closable:      this.initialConfig.closable ||true
            //,deferredRender:false
            ,border:        this.initialConfig.border ||false
            ,frame:        this.initialConfig.border ||false
            ,elements:      this.initialConfig.elements ||"tbar,body,bbar"
            ,tbar:          this.initialConfig.tbar ||[]
            //,collapsible:this.initialConfig.collapsible||false
            ,layout:        "form"
            ,items:         this.items
            ,listeners:     {
                render:  {
                    fn: function(){
                            if(app.loadMask) app.loadMask.hide();
                        }
                    ,scope: this
                }
            }
        });

        if (typeof this.initialConfig.beforeShow == "function")  //            Before SHOW
                fnBeforeShow= this.initialConfig.beforeShow.createDelegate(this, [olPnl])
        if (!fnBeforeShow()) return false;

        this.initialConfig.targetCmp.add(olPnl).show()  // ({xtype:"panel", , items:this.items});

        if (this.initialConfig.resizeable ||false){
            Ext.each(olPnl.items.items, function(pItem){
                var olEl = Ext.getDom(pItem.id);

                var resizer = new Ext.Resizable(olEl.id, {
                    //handles: 'all',
                    minWidth: 200,
                    minHeight: 100,
                    maxWidth: 900,
                    maxHeight: 500,
                    pinned: true
                });

            })
        }

        if (typeof this.initialConfig.afterShow == "function") { //            After SHOW
                fnAfterShow= this.initialConfig.afterShow.createDelegate(this, [olPnl])
                fnAfterShow()
        }
    }).createDelegate(this)                    //eof show

    olCallback = this.afterDirectApi.createDelegate(this, [this.initialConfig]); // Compatibility with early versions that requires an apiUrl at panel level
    if (this.initialConfig.apiUrl && this.initialConfig.apiUrl.length > 0){
        olCallback = this.afterDirectApi.createDelegate(this, [this.initialConfig]);
        loadScript(this.initialConfig.apiUrl, olCallback)
        }
    else olCallback();

}  //eof panel
Ext.reg("Ext.ux.app.panel", Ext.ux.app.panel);