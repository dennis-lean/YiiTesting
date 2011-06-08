$(function() {
function _ajax_request(url, data, callback, failedCallBack, type, method) {
    if (jQuery.isFunction(data)) {
        callback = data;
        data = {};
    }

    return jQuery.ajax({
        type: method,
        url: url,
        data: data,
        success: callback,
        error:  function(XMLHttpRequest, textStatus, errorThrown){
            if(textStatus!='timeout'){
                failedCallBack(XMLHttpRequest, textStatus, errorThrown)
            }else{
                alert('Connection error! Please make sure you are connected to the internet and try again.');
            }
        },
        dataType: 'json',
        timeout:20000
        });
}

jQuery.extend({
    get: function(url, data, callback, failedCallBack, type) {
        return _ajax_request(url, data, callback, failedCallBack, type, 'GET');
    },
    post: function(url, data, callback, failedCallBack, type) {
        return _ajax_request(url, data, callback, failedCallBack, type, 'POST');
    },
    put: function(url, data, callback, failedCallBack, type) {
        return _ajax_request(url, data, callback, failedCallBack, type, 'PUT');
    },
    delete_: function(url, data, callback, failedCallBack, type) {
        return _ajax_request(url, data, callback, failedCallBack, type, 'DELETE');
    },
    history: {
        add: function(url){
            if(typeof(globalHistoryList)!='undefined')
                globalHistoryList.push(url);
            else
                parent.globalHistoryList.push(url);

            if(this.url>=10)
                if(typeof(globalHistoryList)!='undefined')
                    globalHistoryList = globalHistoryList.slice(-10);
                else
                    parent.globalHistoryList = parent.globalHistoryList.slice(-10);
        },
        back: function(){
            return globalHistoryList.pop();
        }
    },
    genHash:  function(obj, ajax){
        var s = new Array();

        for(var key in obj){
            s.push( key+'='+obj[key].toString() );
        }

        if(ajax && ajax==false){
            return '#/?' + s.join('&');
        }

        return '#!/?' + s.join('&');
    },
    setHash: function(id, obj, ajax){
        globalHistoryChanging = true;
        var s = new Array();

        for(var key in obj){
            s.push( key+'='+obj[key].toString() );
        }

        if(ajax && ajax==false){
            window.location.hash = '#/?' + s.join('&');
        }else{
            window.location.hash = '#!/?' + s.join('&');
        }
        $.history.add(id);
    },
    setParentHash: function(id, obj, ajax){
        return;
        parent.globalHistoryChanging = true;
        var s = new Array();

        for(var key in obj){
            s.push( key+'='+obj[key].toString() );
        }

        if(ajax && ajax==false){
            parent.window.location.hash = '#/?' + s.join('&');
        }else{
            parent.window.location.hash = '#!/?' + s.join('&');
        }
        //if last added history url is same as this url (without hash #)
        var hurl = parent.globalHistoryList[parent.globalHistoryList.length-1];
        hurl = hurl.split('#')[0];
        var thisframeHurl = window.location.toString().split('#')[0];
        if( hurl == thisframeHurl){
            parent.globalHistoryList[parent.globalHistoryList.length-1] = window.location.hash;
        }else{
            //first time for this iframe
            $.history.add(id);
        }
    },
    params: function(ajax, toArray) {
        if(window.location.hash=='') return null;

        var pos = 4;
        if(ajax && ajax==false){
            pos = 2;
        }
        var qsParm;

        if(toArray && toArray==true){
            qsParm = [];
        }else{
            qsParm = {};
        }

        var query = window.location.hash.substring(pos);
        var parms = query.split('&');
        for (var i=0; i<parms.length; i++) {
            var pos = parms[i].indexOf('=');
            if (pos > 0) {
                var key = parms[i].substring(0,pos);
                var val = parms[i].substring(pos+1);
                qsParm[key] = val;
            }
        }
        return qsParm;
    },
    alert: function alert(msg, type, title, timeout, width, height, iconStyle){
        var icon='';
        msg = msg.toString();
        
        if(timeout==null)
            timeout = 2500;
        
        if(type==null){
            type='alert';
        }

        switch(type){
            case 'success':icon = 'smile.png';
                            if(title==null) title = 'Operation Success';break;
            case 'ok':icon = 'button_ok.png';
                       if(title==null) title = 'Operation Success';break;
            case 'alert':
                icon = 'alert.png';
                if(timeout==null)
                    timeout=6000;
                if(title==null)
                    title = 'Error Occured';
                break;
            case 'error':
                icon = 'error.png';
                if(timeout==null)
                    timeout=6000;
                if(title==null)
                    title = 'Error Occured';
                break;
        }
        
        msg[0] = msg[0].toUpperCase();
        var hstr = '';
        var hstr2 = '';

        if(width || height){
            hstr = ' style="'
            if(height && height>0)
                hstr += 'height:'+ height +'px;';
            if(width && width>0){
                hstr += 'width:'+ width +'px;';
                hstr2 = 'style="width:'+ (width-112) +'px;"';
            }
            hstr += '"';
        }

        if(iconStyle==null){
            iconStyle = '';
        }else{
            iconStyle = ' style="'+ iconStyle +'"';
        }

        $('body').append('<div class="modalCont" style="opacity:0;top:-140px;cursor:pointer;">'+
            '<div class="modalBox"'+ hstr +'>'+
                '<img src="/img/'+ icon +'" class="modalIcon"'+ iconStyle +'>'+
                '<div class="modalMsgCont"'+ hstr2 +'>'+
                    '<div class="modalTitle">'+ title +'</div>'+
                    '<div class="modalDesc">'+ msg +'</div>'+
                '</div>'+
            '</div>'+
        '</div>');
        $('.modalCont').animate({top:-4,opacity:1}, 420, "easeOutExpo");

        $('.modalCont').unbind('click');

        $('.modalCont').click(function(evt){
            $(this).animate({top:-140}, 500, "easeInQuart");
            var thismc = $(this);
            setTimeout(function(){$(thismc).remove();}, 600);
        });

        if(timeout>0){
            setTimeout(function(){
                $('.modalCont').animate({top:-140}, 500, "easeInQuart");
                setTimeout(function(){$('.modalCont').remove();}, 600);
            }, timeout);
        }
    }
});
});
