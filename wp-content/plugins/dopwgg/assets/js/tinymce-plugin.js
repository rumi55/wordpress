/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : tinymce-plugin.php
* File Version            : 1.0
* Created / Last Modified : 05 February 2012
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : TinyMCE Editor Plugin.
*/

(function(){
    var title, i,
    galleriesData,
    galleries = new Array();

    tinymce.create('tinymce.plugins.DOPWGG', {
        init:function(ed, url){
            title = DOPWGG_tinyMCE_data.split(';;;;;')[0];
            galleriesData = DOPWGG_tinyMCE_data.split(';;;;;')[1];
            galleries = galleriesData.split(';;;');
        },

        createControl:function(n, cm){// Init Combo Box.
            switch (n){
                case 'DOPWGG':
                    var mlb = cm.createListBox('DOPWGG', {
                         title: title,
                         onselect: function(value){
                             tinyMCE.activeEditor.selection.setContent(value);
                         }
                    });

                    for (i=0; i<galleries.length; i++){
                        if (galleries[i] != ''){
                            mlb.add('ID '+galleries[i].split(';;')[0]+': '+galleries[i].split(';;')[1], '[dopwgg id="'+galleries[i].split(';;')[0]+'"]');
                        }
                    }
                    
                    return mlb;
            }

            return null;
        },

        getInfo:function(){
            return {longname  : 'Wall/Grid Gallery',
                    author    : 'Marius-Cristian Donea',
                    authorurl : 'http://www.mariuscristiandonea.com',
                    infourl   : 'http://www.mariuscristiandonea.com',
                    version   : '1.0'};
        }
    });

    tinymce.PluginManager.add('DOPWGG', tinymce.plugins.DOPWGG);
})();