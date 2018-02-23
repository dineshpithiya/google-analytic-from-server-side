function submit_form(formAction,formdata,form,callback,notification)
{
    $.ajax({
        url         : formAction,
        data        : formdata ? formdata : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        beforeSend: function()
        {
            //NProgress.start();
            ajax_loader.start();
        },
        complete: function()
        {
            ajax_loader.stop();
            //NProgress.remove();
        },
        success     : function(data, textStatus, jqXHR)
        {
            try 
            {
                $("input[name=csrf_jt]").val(data.csrf);
                var obj=data;
                if(notification)
                {    
                    if(validation(obj))
                    {
                        callback(data);
                    }
                }
                else
                {
                    callback(data);
                }    
            }
            catch(err) 
            {
                alert(data);
            }
        },
        error: function (jqXHR, exception) 
        {
            //console.log(jqXHR);
            //var object=JSON.parse(jqXHR.responseText).csrf;
            $("input[name=csrf_jt]").val(getCookie('csrf_cookie_jt'));
            ajax_loader.error_handle(jqXHR,exception);
        }
    });
}
function submit_form_progress_bar(formAction,formdata,form,callback,notification)
{
                    $('.myprogress').css('width', '0');
                    $('.msg').text('');
                    var filename = $('#filename').val();
                    var myfile = $('#url').val();
                    var formData = new FormData();
                    formData.append('myfile', $('#url')[0].files[0]);
                    formData.append('filename', filename);
                    $('#btn').attr('disabled', 'disabled');
                     $('.msg').text('Uploading in progress...');


        $.ajax({
        url         : formAction,
        data        : formdata ? formdata : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.myprogress').text(percentComplete + '%');
                                    $('.myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                        return xhr;
        },
        beforeSend: function()
        {
            //NProgress.start();
            ajax_loader.start();
        },
        complete: function()
        {
            ajax_loader.stop();
            //NProgress.remove();
        },
        success     : function(data, textStatus, jqXHR)
        {
            try 
            {
                $("input[name=csrf_jt]").val(data.csrf);
                var obj=data;
                if(notification)
                {    
                    if(validation(obj))
                    {
                        callback(data);
                    }
                }
                else
                {
                    callback(data);
                }    
            }
            catch(err) 
            {
                alert(data);
            }
        },
        error: function (jqXHR, exception) 
        {
            //console.log(jqXHR);
            //var object=JSON.parse(jqXHR.responseText).csrf;
            $("input[name=csrf_jt]").val(getCookie('csrf_cookie_jt'));
            ajax_loader.error_handle(jqXHR,exception);
        }
    });
}
function validation(obj)
{
    switch(obj.status) 
    {
        case 200:
            toastr.success(obj.msg);
            return true;
            break;
        case 304: //Not Modified
            toastr.info(obj.msg);
            return false;
            break;
        case 400: //Bad Request
            toastr.warning(obj.msg);
            return false;
            break;
        case 401: //Unauthorized
            toastr.error(obj.msg);
            return false;
            break;
        case 403: //Forbidden
            toastr.warning(obj.msg);
            return false;
            break;
        case 404: //Not Found
            toastr.info(obj.msg);
            return false;
            break; 
        case 406: //Not Acceptable
            toastr.info(obj.msg);
            return false;
            break;                    
        default:
           console.log("obj: "+obj);
           alert('set proper response');
    }
}
//get cookie values like getCookie('xyz cookie name')
function getCookie(name) 
{
    function escape(s) 
    { 
        return s.replace(/([.*+?\^${}()|\[\]\/\\])/g, '\\$1'); 
    };
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
    return match ? match[1] : null;
}
 
function _post(url,data,callback)
{
    ajax_loader.start();
    data['csrf_jt']=getCookie('csrf_cookie_jt');
    $.post(url, data, function(data) 
    {
        callbackurl(data); 
    }).success(function() 
    {
    }).error(function(jqXHR,e)
    {
        ajax_loader.error_handle(jqXHR,e);
    }).complete(function() 
    {
        ajax_loader.stop();
    });
}

function _postweb9(url,data,callback)
{
    ajax_loader.start();
    data['csrf_jt']=getCookie('csrf_cookie_jt');
    $.post(url, data, function(data) 
    {
        callback(data); 
    }).success(function() 
    {
    }).error(function(jqXHR,e)
    {
        ajax_loader.error_handle(jqXHR,e);
    }).complete(function() 
    {
        ajax_loader.stop();
    });
}

function _post1(url,data,callback)
{
    ajax_loader.start();
    data['csrf_jt']=getCookie('csrf_cookie_jt');
    $.post(url, data, function(data)
    {
        window[callback](data);
    }).success(function() {
    }).error(function(jqXHR,e)
    {
        ajax_loader.error_handle(jqXHR,e);
    }).complete(function()
    {
        ajax_loader.stop();
    });
}
/*Below function added to get random id*/
function getGuid() {
    function s4() {
          return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return (s4() + s4() + '-' + s4() + '-' + s4() + '-' +
          s4() + '-' + s4() + s4() + s4()).toUpperCase(); 
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
/*validation function */
function multipleEmailValidation(value) {
    var emails = value.split(','),
        valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
        value = emails[i];
        valid = valid && isEmail(value);
    }
    return valid;
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
$(document).on('click','.flowpaper_tblabelbutton',function (e) {
    that = this;
    position = $(that).position();
    $('#toolbar_documentViewer_annotations_popup').css('left',position.left+'px');
});