//检查图片是否存在
function CheckImgExists(imgurl) {
    var ImgObj = new Image(); 
    ImgObj.src = imgurl;
    if (ImgObj.fileSize > 0 || (ImgObj.width > 0 && ImgObj.height > 0)) {
        return true;
    } else {
        return false;
    }
}

function haspro(e,key) { 
    return e.hasOwnProperty(key); 
}
function getCookie(name){ 
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)){
        return unescape(arr[2])
    }else {
        return ''
    }
}
function setCookie(name, value, e) {
    var dt = new Date();
    var e = arguments[2] || ckhour;
    if (e) {
        dt.setMinutes(dt.getMinutes() + pi(e));
    }
    var cookietemp = escape(name) + '=' + escape(value) + (e ? ';path=/;expires=' + dt.toGMTString() : '');
    d.cookie = cookietemp;
}
function getuin(){ 
    return this.getCookie("pt2gguin") ? this.getCookie("pt2gguin").substr(1).replace(/\b(0+)/gi,"") : ''; 
}

function inarr(a, e){
    var s = String.fromCharCode(2);
    var r = new RegExp(s + e + s);
    return (r.test(s + a.join(s) + s));
}


/*获取连接参数*/
function getString(url,sName){  
    var sRE = "([?&])" + sName + "=([^&]*)";  
    var oRE = new RegExp(sRE);  
    if (oRE.test(url)) {  
        return RegExp["$2"];  
    }  
    else {  
        return null;  
    }  
}

//改变请求链接
function changeUrl(key, value)
{
    var url = document.location.href;
    var old_value = getUrlPara(key);
    
    if(old_value!= "")
    {
        url = url.replace(key+"="+old_value, key+"="+value)
    }
    else
    {
        url = url.replace(key+"=", "");
        if(url.indexOf("?")>0)
        {
            url += "&"+key+"="+value;
        }
        else
        {
            url += "?"+key+"="+value;
        }
    }
    document.location.href = url;
}

function gE(id){
    return document.getElementById(id);
}

function datetimeToSeconds(datetime) { //这个函数在highchart对象里应该有
    var dateArr = datetime.split(" ");
    var dateArr_0 = dateArr[0].split("-");
    var dateArr_1 = dateArr[1].split(":");
    return Date.UTC(dateArr_0[0],dateArr_0[1]-1,dateArr_0[2],dateArr_1[0],dateArr_1[1],dateArr_1[2]);
}

//比较两个日期相差多少天
function daydiff(startDate, endDate) {
    var startTime = (new Date(startDate)).getTime();
    var endTime = (new Date(endDate)).getTime();
    var days= parseInt(Math.abs(startTime - endTime ) / (1000*60*60*24));
    return days;
}
//获取指定日期的前后几天的日期
function GetDateStr(startDate,AddDayCount) {
    var date = new Date(startDate);
    date.setDate(date.getDate()+AddDayCount);//获取AddDayCount天后的日期
    var y = date.getFullYear();
    var m = date.getMonth()+1;//获取当前月份的日期
    var d = date.getDate();
    return y+"-"+m+"-"+d;
}

function getToday(){
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth()+1;//获取当前月份的日期
    var d = date.getDate();
    return y+"-"+m+"-"+d;
}