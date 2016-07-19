Ext.ux.OnDemandLoadByAjax = function(){

    loadComponent = function(component, callback){
        handleSuccess = function(response, options) {
            var type = component.substring(component.lastIndexOf('.'));
            var head = document.getElementsByTagName("head")[0];
            if (type === ".js") {
                var js = document.createElement('script');
                js.setAttribute("type", "text/javascript");
                js.text = response.responseText;
                if (!document.all) {
                    js.innerHTML = response.responseText;
                }

                head.appendChild(js);
            }

            if(typeof callback == "function"){
                if(document.all) {
                    callback();
                } else {
                    callback.defer(50);
                }
            };
        };

        handleFailure = function(response, options) {
            alert('Dynamic load script: [' + component + '] failed!');
        };

        Ext.Ajax.request({
            url: component,
            method: 'GET',
            success: handleSuccess,
            failure: handleFailure,
            disableCaching : false
        });
    };

    return {
        load: function(components, callback){
            Ext.each(components, function(component){
                loadComponent(component, callback);
            });
        }
    };
}();