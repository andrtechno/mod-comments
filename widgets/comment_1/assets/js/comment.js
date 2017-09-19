
if (typeof jQuery === 'undefined') {
  throw new Error('Comments requires jQuery');
}
if (typeof $.session === 'undefined') {
  throw new Error('Comments requires jQuery.session');
}
//if (typeof jQuery.jGrowl === 'undefined') {
//  throw new Error('Comments requires jQuery.jGrowl');
//}



/*new comments js functions
var fn = {};
fn.comment = {
  add:function(form_id){
      var form = $(form_id);
        this.ajax(form.attr('action'),form.serialize(),function(data, textStatus, xhr){
            console.log(xhr);
            if(data.errors){
                $.jGrowl(data.errors, {
                    position:"bottom-right"
                });
            }else{
                $.jGrowl(data.message, {
                    position:"bottom-right"
                });
                 
            }
        },'POST','json');
      return false;
  },
    ajax:function(url,data,success,type,dataType){
        console.log(type);
        type = (type==undefined)?'POST':type;
        dataType = (dataType==undefined)?'html':dataType;
        $.ajax({
            url: url,
            type:type,
            data:data,
            dataType:dataType,
            success:success
        });
    }
};

*/
(function( $ ){
    var methods = {
        init : function( options ) { 
            var settings = $.extend({
                placeholder: "_",
                completed: null
            }, options);
            console.log(settings);
        },
        currentTime:function(){
            return Math.round((new Date()).getTime()/1000)
        },

        remove : function(config) {
            var container = this.selector;
            if(methods.currentTime() < config.time){

                $('body').append('<div id="dialog"></div>');
                $('#dialog').dialog({
                    modal: true,
                    resizable: false,
                    width:'200',
                    height:150,
                    closeOnEscape:true,
                    open:function(){
                        $(this).html('Удалить комментарий?');
                    },
                    close: function () {
                        $(this).remove();
                    },
                    buttons:[{
                        text:'OK',
                        click:function(){
                            var dialog = this;
                            $(dialog).remove();
                            xhr = $.ajax({
                                url:'/comments/delete/'+config.pk,
                                type:'POST',
                                dataType:'json',
                                data:{
                                    token:token
                                },
                                success:function(data){
                                    if(data.code=='success'){
                                        //$(dialog).dialog('close');
                                        /*Обновляем список комментариев*/
                                        //$.fn.yiiListView.update('comment-list');
                                        $.jGrowl(data.flash_message,{
                                            position:"top-right"
                                        }); 
                                    } else if (data.code=='fail'){
                                        $.jGrowl(data.response.text,{
                                            position:"top-right"
                                        }); 
                                    }
                                }
                            });

                        }
                    },{
                        text:'Cancel',
                        click:function(){
                            $(this).dialog('close');
                        }
                    }]
                });
            }else{
                $.jGrowl('Time Out!!!',{
                    position:"top-right"
                });
                $('#comment-panel'+config.pk).remove();
            }
        },
        update : function(config) {
            var container = this.selector;
            var xhr;
            if(methods.currentTime() < config.time){
                $('body').append('<div id="dialog"></div>');
                $('#dialog').dialog({
                    modal: true,
                    resizable: false,
                    width:'60%',
                    height:200,
                    closeOnEscape:true,
                    open:function(){
                        var dialog_container = this;
                        xhr = $.ajax({
                            url:'/comments/edit',
                            type:'POST',
                            data:{
                                _id:config.pk,
                                token:token
                            },
                            success:function(data){
                                $(dialog_container).html(data);
                                $('.ui-dialog-buttonset').show();

                            }
                        });
                    },
                    close: function () {
                        $(this).remove();
                        xhr.abort();
                    },
                    create: function() {
                        $('.ui-dialog-buttonset').hide();
                    },
                    buttons:[{
                        text:'Save',
                        click:function(){
                            var data = $('form:first',this).serialize();
                            var dialog = this;
                            xhr = $.ajax({
                                url:'/comments/edit',
                                type:'POST',
                                dataType:'json',
                                data:data + "&_id="+config.pk,
                                success:function(data){
                                    if(data.code=='success'){
                                        $(dialog).dialog('close');
                                        $(container).html(data.response);
                                        $.jGrowl(data.flash_message,{
                                            position:"top-right"
                                        }); 
                                    } else if (data.code=='fail'){
                                        $.jGrowl(data.response.text,{
                                            position:"top-right"
                                        }); 
                                    }
                                }
                            });

                        }
                    },{
                        text:'Cancel',
                        click:function(){
                            $(this).dialog('close');
                        }
                    }]
                        
                });
            }else{
                $.jGrowl('Time Out!!!',{
                    position:"top-right"
                });
                $('#comment-panel'+config.pk).remove();
            }
        }
    };

    $.fn.comment = function( method ) {
    
        // логика вызова метода
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.comment' );
        } 
    };

})(jQuery);
