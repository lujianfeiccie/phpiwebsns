//封装$
function $(id_value){
	return document.getElementById(id_value);
}

//处理返回结果
function act_result(content){
	var exp_str=/error:/;
	if(exp_str.test(content)){
		var return_array=content.split(":");
		var error_str = (return_array[1]=='') ? '操作发生错误' : return_array[1];
		alert(error_str);
	}else{
		$("cms_content").innerHTML=content;
		pick_script(content);
	}
}

//提取字符串中的js执行
function pick_script(content){
	content.replace(/<script type=[\"\']text\/javascript[\"\']>([^<]+)<\/script>/g,function($whole,$match){
			if(window.document.all){
				window.execScript($match);
			}else{
				window.eval($match);
			}
		}
	)
}

//隐藏显示
function show_hidden(show_id,hidden_id){
	if(show_id){
		$(show_id).style.display='';
	}
	if(hidden_id){
		$(hidden_id).style.display='none';
	}
}

//修改数据
function edit(){
	var form_obj=$(arguments[0]);
	form_obj.style.display='';
	var exp_str=/\&$/;
	if(!exp_str.test(form_obj.action)){
		form_obj.action+='&';
	}
	form_obj.action+=arguments[1];
	for(i=2;i < arguments.length;i++){
		form_obj[i-2].value=arguments[i];
	}
}

//修改管理员分组
function ajax_send(url_str,send_data,show_id){
	var send_type='post';
	if(send_data==''){
		var send_type='get';
	}
	var send=new Ajax();
	send.getInfo(url_str,send_type,"app",send_data,function(c){if(show_id) $(show_id).innerHTML=c;});
}

//批量全选
function form_select(obj,type_value){
/*type_value值:
0:取消选择;
1:开始选择;
2:反向选择;
*/
	var form_obj=$(obj);
	var input_obj=form_obj.getElementsByTagName('input');
	for(i=0;i<input_obj.length;i++){
		if(input_obj[i].type=='checkbox'){
			if(type_value==0){
				input_obj[i].checked='';
			}else if(type_value==1){
				input_obj[i].checked='checked';
			}else if(type_value==2){
				if(input_obj[i].checked==true){
					input_obj[i].checked='';
				}else{
					input_obj[i].checked='checked';
				}
			}
		}
	}
}

//添加编辑器图片
function AddContentImg(ImgName,classId){
	var obj = $("content").previousSibling.children[0];
	obj.innerHTML = obj.innerHTML + "<br><IMG src='../"+ImgName+"' onload='fixImage(this,420,0)' classId="+classId+" /><br>";
}

//强制控制图片
function fixImage(i,w,h){
  var ow = i.width;
    var oh = i.height;
    var rw = w/ow;
    var rh = h/oh;
    var r = Math.min(rw,rh);
    if (w ==0 && h == 0){
        r = 1;
    }else if (w == 0){
        r = rh<1?rh:1;
    }else if (h == 0){
        r = rw<1?rw:1;
    }
    if (ow!=0 && oh!=0){
    i.width = ow * r;
      i.height = oh * r;
    }else{
      var __method = this, args = $A(arguments);
        window.setTimeout(function() {
          fixImage.apply(__method, args);
        }, 200);
    }
    i.onload = function(){}
}

//模拟form提交，地址自定义
function submit_form(obj_id,action_url){
	$(obj_id).action = action_url ? action_url : $(obj_id).action;
	if($(obj_id).getAttributeNode('target').value==''){
		$(obj_id).submit();
	}else{
		$(obj_id).onsubmit();
	}
}

//照片展示
function img_priview(pic_src){
	$('priview_img').src=pic_src;
	$('priview_img_act').href='index.php?app=act&upload&del&src='+pic_src;
	$('img_priview').style.display='';
	$('thumb_iframe').style.display='none';
	if(document.getElementsByName('type_id')){
		var type_array=document.getElementsByName('type_id');
		for(i=0;i<type_array.length;i++){
			type_array[i].disabled=true;
		}
	}
}

//照片展示
function flash_priview(flash_src){
	$('priview_flash').src=flash_src;
	$('priview_flash_act').href='index.php?app=act&upload&del&src='+flash_src;
	$('flash_priview').style.display='';
	$('flash_iframe').style.display='none';
	if(document.getElementsByName('type_id')){
		var type_array=document.getElementsByName('type_id');
		for(i=0;i<type_array.length;i++){
			type_array[i].disabled=true;
		}
	}
}

//删除thumb照片
function img_def(c){
	if(c=='success'){
		$('priview_img').src='';
		$('priview_img_act').href='';
		$('img_priview').style.display='none';
		$('thumb_iframe').style.display='';
		$('upload_name').value='';
	}else{
		alert(c);
		window.history.go(-1);
	}
	if(document.getElementsByName('type_id')){
		var type_array=document.getElementsByName('type_id');
		for(i=0;i<type_array.length;i++){
			type_array[i].disabled=false;
		}
	}
}

//删除flash
function flash_def(c){
	if(c=='success'){
		$('priview_flash').src='';
		$('priview_flash_act').href='';
		$('flash_priview').style.display='none';
		$('flash_iframe').style.display='';
		$('upload_name').value='';
	}else{
		alert(c);
		window.history.go(-1);
	}
	if(document.getElementsByName('type_id')){
		var type_array=document.getElementsByName('type_id');
		for(i=0;i<type_array.length;i++){
			type_array[i].disabled=false;
		}
	}
}

//频道div隐藏切换
function channel_div(type_id){
	var is_show_obj=document.getElementsByName('is_show');
	var is_digg_obj=document.getElementsByName('is_digg');
	if(type_id==0){
		show_hidden('','outer_link');
		show_hidden('','single_page');
		var is_able=false;
	}else if(type_id==1){
		show_hidden('single_page','outer_link');
		var is_able=true;
	}else if(type_id==2){
		show_hidden('outer_link','single_page');
		var is_able=true;
	}
	for(i=0;i<2;i++){
		is_show_obj[i].disabled=is_able;
		is_digg_obj[i].disabled=is_able;
	}
}

//ads的div的隐藏切还
function ads_div(type_id){
	if(type_id==0){
		show_hidden('thumb_iframe','');
		show_hidden('','flash_iframe');
		show_hidden('','txt_type');
		show_hidden('','link_type');
		$('link').disabled=false;
	}else if(type_id==1){
		show_hidden('','thumb_iframe');
		show_hidden('flash_iframe','');
		show_hidden('','txt_type');
		show_hidden('','link_type');
		$('txt_src').value='';
		$('link').disabled=false;
	}else if(type_id==2){
		show_hidden('','thumb_iframe');
		show_hidden('','flash_iframe');
		show_hidden('txt_type','');
		show_hidden('','link_type');
		$('link').disabled=true;
		$('link').value='';
	}else if(type_id==3){
		show_hidden('','thumb_iframe');
		show_hidden('','flash_iframe');
		show_hidden('','txt_type');
		show_hidden('link_type','');
		$('link').disabled=false;
	}
}