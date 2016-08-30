if(window.location.host.indexOf("ied")>0){
    document.domain="ied.com";
}
else{
    document.domain="qq.com";
}

function addUseTime(id,needBack){
	$.post("index.php?module=totalApp&action=totalAppList&func=addUseTimes",{id:id,needBack:needBack},function(result){
		if(result.retCode==0){
			$('#useTime_'+result.id).text(parseInt($('#useTime_'+result.id).text())+1);
		}
	},'json');
	return true;

}
