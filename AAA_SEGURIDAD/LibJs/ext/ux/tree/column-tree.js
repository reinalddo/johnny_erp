/*
 * Ext JS Library 2.0 Alpha 1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    var tree = new Ext.tree.ColumnTree({
        el:'tree-ct',
        width:552,
        autoHeight:true,
        rootVisible:false,
        autoScroll:true,
        title: 'Example Tasks',
        
        columns:[{
            header:'Cuenta',
            width:350,
            dataIndex:'task'
        },{
            header:'Saldo Anterior',
            width:100,
            dataIndex:'duration'
        },{
            header:'Debitos',
            width:100,
            dataIndex:'user'
        },{
            header:'Creditos',
            width:100,
            dataIndex:'user'
        },{
            header:'Saldos',
            width:100,
            dataIndex:'user'
        }],

        loader: new Ext.tree.TreeLoader({
            dataUrl:'column-data.json',
            uiProviders:{
                'col': Ext.tree.ColumnNodeUI
            }
        }),

        root: new Ext.tree.AsyncTreeNode({
            text:'PRUEBA'
        })
    });
    tree.render();
});
