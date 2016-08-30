var schedule = {};
$(document).ready(function(){
    schedule.allSelectGame();
    //全选游戏业务
    $('#game_all').click(function(){
        if($(this).prop("checked") == true){// 全选    
            $("input[type=checkbox][name=game_box]").each(function(){  
                    $(this).prop("checked", true); 
                });    
        } else {// 取消全选    
            $("input[type=checkbox][name=game_box]").each(function(){    
                $(this).prop("checked", false);  
            });    
        }  
        schedule.allSelectGame();
    });
    $($('input[id^=game_]')).click(function(){
        schedule.allSelectGame();
    });
    //查找功能   
    $('#select-input').bind('input propertychange', schedule.selectGame);
    
});
//所有选中的游戏业务
schedule.allSelectGame = function(){
    var gameAppStr = '';
    $("input[type=checkbox][name=game_box]:checked").each(function(){
        gameAppStr = gameAppStr+','+$(this).val();
    });    
    $('input[name=gameAppStr]').attr('value',gameAppStr.substr(1));
}

schedule.selectGame = function(){   
    var value = $('#select-input').val();
    var all = $('#game_all').attr('data-count');
    all = all.split('-');
    if(value){
        var re = /^\s+|\s+$/g;
        value = value.replace(re, '').toLowerCase();//去掉收尾空格
        var searchArr = value.split(' ');       
        $("input[type=checkbox][name=game_box]").each(function(){            
            var _html = $(this).attr('data-name').toLowerCase();
            var bFound=false;
            for(var j=0;j<searchArr.length;j++){
                if(_html.search(searchArr[j])!=-1){
                    bFound=true;
                    break;
                }
            }
            if(bFound){
                $(this).parent().parent().show();
            }else{
                $(this).parent().parent().hide();
            }
        });
        $('#game_all').parent().parent().hide();
    }else{
        $('#game_all').parent().parent().show();
        $("input[type=checkbox][name=game_box]").each(function(){
            $(this).parent().parent().show();
        });
        if(all[0] == all[1]){
            $('#game_all').prop('checked',true); 
        }
    }
}

schedule.hideGameList = function(id){
    $('body').mousedown(function(e){
        if($(e.target).closest('#'+id).length == 0){$('#'+id).hide()}
        }
    );
    
}