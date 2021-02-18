if(typeof $==='undefined') $=jQuery;

function EDC(){
	this.initializeTabs=function(container,n){
		if(n==null) n=0;
		if(container==null) return false;
		container=$(container);
		var tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
		if(!tabs_holder) return false;
		var tabs=tabs_holder.find('.navigation li');
		var boxes=tabs_holder.find('.items .edc_tab');
		if(tabs.length!=boxes.length) return false;
		if(tabs.get(n)==null) n=0;
		$(tabs.get(n)).addClass('active');
		$(boxes.get(n)).addClass('active');
		tabs.each(function(i){
			$(this).find('a').on('click',function(e){ e.preventDefault(); });
			if(i!=n){ $(boxes.get(i)).removeClass('active'); }
			$(this).on('click',(function(c,t,b){ return function(){
				if($(this).hasClass('active')) return false;
				var sel=0;
				var obj=this;
				t.each(function(index){
					$(t.get(index)).removeClass('active');
					$(b.get(index)).removeClass('active');
					if(t.get(index)===obj) sel=index;
				});
				$(t.get(sel)).addClass('active');
				$(b.get(sel)).addClass('active');
			}})(container,tabs,boxes));
		});
	}
	this.setTab=function(container,n){
		if(n==null) n=0;
		if(!container) return false;
		container=$(container);
		var tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
		if(!tabs_holder) return false;
		var tabs=tabs_holder.find('.navigation li');
		tabs.each(function(i){ if(i==n) tabs.get(i).click(); });
	}
	this.initMediaButtons=function(){
		var btns=document.body.querySelectorAll('.edc_media_loader');
		for(var i=0;i<btns.length;++i){
			$(btns[i]).on('click',(function(obj){ return function(){
				if(!obj.media_window){
					let btn=$(this);
					obj.media_window = wp.media({
						title: 'Insert a media',
						multiple: obj.is(this.dataset.multiple),
						button: {text: 'Insert'}
					});
					obj.media_window.on('select',function(){
						let m=obj.media_window.state().get('multiple');
						let value='';
						if(m){
							let atts=obj.media_window.state().get('selection');
							let values=[];
							html='';
							atts.map(function(attachment){
								attachment = attachment.toJSON();
								values.push(attachment.id);						
								html+=attachment.url+'<br>';
							});
							if($(btn).attr('data-type')=='file'){
								$(btn).parents('.label').find('.attachment_text').html(html);
							}
							value=values.join(',');
						}else{
							let attachment=obj.media_window.state().get('selection').first().toJSON();
							value=attachment.id;
							if($(btn).attr('data-type')=='file'){
								$(btn).parents('.label').find('.attachment_text').html(attachment.url);						
							}else{
								$(btn).parents('.label').find('.attachment_holder').css({'background-image':'url(\''+attachment.url+"')"});
							}
						}
						$(btn).parents('form').find('input[name="'+$(btn).attr('data-field')+'"]').val(value);
					});
				}
				obj.media_window.open();
			}})(this));
		}
		var btns=document.body.querySelectorAll('.edc_media_remover');
		
		for(var i=0;i<btns.length;++i){
			$(btns[i]).on('click',(function(obj){ return function(){				
				if($(this).attr('data-type')=='file'){
					$(this).parents('.label').find('.attachment_text').html('');				
				}else{
					var img=$(this).parents('.label').find('.attachment_holder');
					img.css({'background-image':'url(\''+img.attr('data-empty')+"')"});
				}
				$(this).parents('form').find('input[name="'+$(this).attr('data-field')+'"]').val('');
			}})(this));
		}
	}
	this.is=function(v){
		let res=false;
		if(typeof v==='boolean') res=v;
		else if(typeof v==='string'){
			v=v.trim().toString().toLowerCase();
			res=v=='1' || v=='yes' || v=='true' || v=='on' || v=='y' || v=='ya';
		}
		return res;
	}
	this.submit=function(el,params){
		if(!el) return false;
		if($(el).hasClass('loading')) return false;
		var f=$(el).parents('form');
		if(!f || !f.length) return false;
		$(el).addClass('loading');
		$(el).attr('disabled','true');
		var result=f.find('.result');
		result.removeClass('success');
		result.removeClass('error');
		//console.log(f.get(0).value);
		//return;
		var fd = new FormData(f.get(0));
		var tiny_fields=f.get(0).querySelectorAll('.wp-editor-area');
		for(var i=0;i<tiny_fields.length;++i){
			if(tiny_fields[i].name && tinyMCE.get(tiny_fields[i].name)) fd.set(tiny_fields[i].name,tinyMCE.get(tiny_fields[i].name).getContent());
		//	fd.append(f[i].name,f[i].value);
		}
		//console.log();
		//wp-editor-area
		//tinyMCE.getContent
		var obj=this;
		console.log(fd);
		return;
		$.ajax({
			url: window.location,
			type: 'post',
			data: fd,
			processData: false,
			contentType: false,
			success: function (data){
				console.log(data);
				var res='An error occured. Try again later.';
				var res_class='error';
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						res_class='success';
						if(typeof obj[params['callback']]==='function') obj[params['callback']].call(obj,data);
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						res_class='error';
						if(typeof obj[params['ecallback']]==='function') obj[params['ecallback']].call(obj);
					}
				}catch(ex){}
				result.addClass(res_class)
				result.html(res);
				$(el).removeClass('loading');
				el.disabled=false;
				el.removeAttribute('disabled');
			},
			error: function(data){
				console.log(data);
				var res='An error occured. Try again later.';
				var res_class='error';
				result.addClass(res_class)
				result.html(res);
				$(el).removeClass('loading');
				el.disabled=false;
				el.removeAttribute('disabled');
			},
		});
	}
	this.closePopup=function(eid){	
		if(typeof eid==='string'){
			var el=document.getElementById(eid);
			if(!el) el=document.querySelector(eid);
		}else el=eid;
		if(!el) return false;
		el=$(el).hasClass('popup') ? $(el) : $(el).parents('.popup');
		el.removeClass('active');
	}
	this.closeThisPopup=function(el,ev){
		if(!ev || !ev.target || ev.target!==el) return false;
		this.closePopup(el);
	}
	this.windowLoading=function(hide){
		if(!document.body.querySelector('#edc_window_loading')){
			var par=document.body.querySelector('.edc_admin_page');
			if(!par) return false;
			var el=document.createElement('div');
			el.innerHTML='<div><svg aria-hidden="true" class="svg_inline edc_spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg></div>';
			el.id='edc_window_loading';
			par.appendChild(el);
		}
		if(typeof hide==='undefined') hide=true;
		if(hide) $('#edc_window_loading').addClass('active');
		else $('#edc_window_loading').removeClass('active');
	}
	this.LANG=function(code,def){
		if(typeof def==='undefined') def='';
		if(typeof JS_LANG==='undefined' || typeof JS_LAN!=='Object') return def;
		return JS_LANG[code] ? JS_LANG[code] : def;
	}
	this.init=function(){
		window.addEventListener('load',(function(obj){ return function(){
			$('select.with_chosen').chosen({
				width:'100%',
				disable_search_threshold: 5,
				placeholder_text_multiple: 'Bitte auswählen',
			});
			$(".with_calendar").datepicker({
				dateFormat : 'dd.mm.yy',
			});
			$(".with_calendar").attr('autocomplete','off');
			obj.initMediaButtons();
			obj.initTariffPage();
		}})(this));
	}
	this.init();
}
EDC.prototype.addPostcode=function(){
	$('#postcode_popup').addClass('active');
	$('#postcode_popup .add_title').addClass('active');
	$('#postcode_popup .edit_title').removeClass('active');
	$('#postcode_popup form').get(0).reset();
	$('#postcode_popup form input[name="edc_postcode"]').val('new');
}
EDC.prototype.addStreet=function(el, value = ""){
	$(el).before(`<div class="field"><input type="text" class="street" name="street_list[]" value="${value}"><div class="close" onclick="edc_admin.removeStreet(this)">×</div></div>`);
}
EDC.prototype.removeStreet=function(el) {
	let field = el.closest(".field");
	if(field) $(field).remove();
}
EDC.prototype.editPostcode=function(id,code,name,type){
	$('#postcode_popup').addClass('active');
	$('#postcode_popup .edit_title').addClass('active');
	$('#postcode_popup .add_title').removeClass('active');
	$('#postcode_popup form').get(0).reset();
	$('#postcode_popup form input[name="edc_postcode"]').val(id);
	$('#postcode_popup form input[name="name"]').val(name);
	$('#postcode_popup form select[name="type"]').val(type).trigger("chosen:updated");
	$('#postcode_popup form input[name="code"]').val(code);
	$('#postcode_popup .street_list .field').remove();
	if(typeof street_list !== "undefined" && street_list[id]) {
		$.each(street_list[id], function (i, value) {
			EDC.prototype.addStreet($('#postcode_popup .street_list .street_add'), value);
		});
	} else {
		EDC.prototype.addStreet($('#postcode_popup .street_list .street_add'));
	}
}
EDC.prototype.importPostcodes=function(){
	if(!document.body.querySelector('#postcodes_import_form')){
		var f=document.createElement('form');
		f.style.display='none';
		f.action='';
		f.method='POST';
		f.enctype='multipart/form-data';
		f.id='postcodes_import_form'
	}else f=document.body.querySelector('#postcodes_import_form');
	f.innerHTML='<input type="hidden" name="postcodes_import" value="1"><input type="file" name="postcodes">';
	document.body.appendChild(f);
	var obj=this;
	f.querySelector('input[type="file"]').addEventListener('change',function(){
		var fd = new FormData(this.form);
		obj.windowLoading();
		$.ajax({
			url: window.location,
			type: 'post',
			data: fd,
			processData: false,
			contentType: false,
			success: function (data){
				var res='An error occured. Please try again later.';
				var type='error';
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){				
				alert(EDC.LANG('error','Error'));
				obj.windowLoading(false);
			},
		})
	});
	f.querySelector('input[type="file"]').click();
}
EDC.prototype.deletePostcode=function(pid,c){
	if(confirm(c)){
		this.windowLoading();
		var obj=this;
		$.ajax({
			url: window.location,
			type: 'post',
			data: 'edc_remove_postcode='+pid.toString(),
			success: function (data){
				var res='An error occured. Please try again later.';
				var type='error';
				console.log(data);
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){
				alert(EDC.LANG('error','Error'));
				obj.windowLoading(false);
			},
		});
	}	
}
EDC.prototype.removeSelectedPostcodes=function(){
	if(!document.body.querySelector('#postcodes_table')){ alert(this.LANG('error','Error')); return false; }
	
	if(confirm(this.LANG('undone_action','Are you sure? This action can not be undone.'))){
		var checks=document.body.querySelectorAll('#postcodes_table tbody tr .check-column input[type="checkbox"]');
		var ids=[];
		for(var i=0;i<checks.length;++i){
			if(checks[i].checked){
				ids.push(checks[i].name.replace('postcode_',''));
			}
		}
		if(ids.length==0){ alert(this.LANG('no_checkbox_checked','No items has been selected')); }	
		this.windowLoading();
		var obj=this;
		$.ajax({
			url: window.location,
			type: 'post',
			data: 'edc_remove_postcodes='+ids.join(','),
			success: function (data){
				var res='An error occured. Please try again later.';
				var type='error';
				console.log(data);
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){
				alert(obj.LANG('error','Error'));
				obj.windowLoading(false);
			},
		});
	}
}
EDC.prototype.getPostcodesForImporter=function(){
	
	if(!document.body.querySelector('#postcodes_table')){ alert(this.LANG('error','Error')); return false; }
	var checks=document.body.querySelectorAll('#postcodes_table tbody tr .check-column input[type="checkbox"]');
	var ids=[];
	for(var i=0;i<checks.length;++i){
		if(checks[i].checked){
			ids.push(checks[i].name.replace('postcode_',''));
		}
	}
	if(ids.length==0){ alert(this.LANG('no_checkbox_checked','No items has been selected')); }	
	var el=document.createElement('textarea');
	el.value=ids.join(',');
	el.style.position='absolute';
	el.style.left='-9999%';
	el.style.top='-9999%';
	document.body.appendChild(el);
	el.select();
	console.log(el);
	document.execCommand('copy');
	document.body.removeChild(el);
}
EDC.prototype.loadCustomOptions=function(el){
	$.ajax({
			url: window.location,
			type: 'post',
			data: 'get_tariff_options='+el.value+'&tariff='+el.form.edc_tariff.value,
			success: function (data){
				document.body.querySelector('#custom_options .holder').innerHTML=data;
			},
	});
}
EDC.prototype.tariffSubmit=function(data){
	console.log(data);
	if(data['redirect']) window.location.href=data['redirect'];
}

EDC.prototype.deleteTariff=function(pid,c){
	if(confirm(c)){
		this.windowLoading();
		var obj=this;
		$.ajax({
			url: window.location,
			type: 'post',
			data: 'edc_remove_tariff='+pid.toString(),
			success: function (data){
				var res='An error occured. Please try again later.';
				var type='error';
				console.log(data);
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){
				alert(obj.LANG('error','Error'));
				obj.windowLoading(false);
			},
		});
	}	
}
EDC.prototype.removeSelectedTariffs=function(){
	if(!document.body.querySelector('#tariffs_table')){ alert(this.LANG('error','Error')); return false; }
	
	if(confirm(this.LANG('undone_action','Are you sure? This action can not be undone.'))){
		var checks=document.body.querySelectorAll('#tariffs_table tbody tr .check-column input[type="checkbox"]');
		var ids=[];
		for(var i=0;i<checks.length;++i){
			if(checks[i].checked){
				ids.push(checks[i].name.replace('tariff_',''));
			}
		}
		if(ids.length==0){ alert(this.LANG('no_checkbox_checked','No items has been selected,')); }	
		this.windowLoading();
		var obj=this;
		$.ajax({
			url: window.location,
			type: 'post',
			data: 'edc_remove_tariffs='+ids.join(','),
			success: function (data){
				var res='An error occured. Please try again later.';
				var type='error';
				console.log(data);
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){
				alert(obj.LANG('error','Error'));
				obj.windowLoading(false);
			},
		});
	}
}
EDC.prototype.importTariffs=function(){
	if(!document.body.querySelector('#tariffs_import_form')){
		var f=document.createElement('form');
		f.style.display='none';
		f.action='';
		f.method='POST';
		f.enctype='multipart/form-data';
		f.id='tariffs_import_form'
	}else f=document.body.querySelector('#tariffs_import_form');
	f.innerHTML='<input type="hidden" name="tariffs_import" value="1"><input type="file" name="tariffs">';
	document.body.appendChild(f);
	var obj=this;
	f.querySelector('input[type="file"]').addEventListener('change',function(){
		var fd = new FormData(this.form);
		obj.windowLoading();
		$.ajax({
			url: window.location,
			type: 'post',
			data: fd,
			processData: false,
			contentType: false,
			success: function (data){
				console.log(data);
				var res='An error occured. Please try again later.';
				var type='error';
				try{
					data=JSON.parse(data);
					if(data['type']=='success'){					
						if(data['success_text']){ res=data['success_text']; }
						else{ res='Success'; }
						type='success';
					}else{
						if(data['error_text']){	res=data['error_text'];	}
						type='error';
					}
				}catch(ex){}
				if(type=='success'){
					window.location=window.location.href;
				}else{
					alert(res);
					obj.windowLoading(false);
				}
			},
			error : function (data){				
				alert(obj.LANG('error','Error'));
				obj.windowLoading(false);
			},
		});
	});
	f.querySelector('input[type="file"]').click();
}
EDC.prototype.switchGroup=function(ev,el){
	if(!ev || !ev.target || !ev.target.className || (!$(ev.target).hasClass('form_title') && !$(ev.target).hasClass('group'))) return false;
	if(!el || $(el).hasClass('processing')) return false;
	
	$(el).addClass('processing');
	if($(el).hasClass('form_title')){
		$(el).parents('.group').addClass('processing');
	}else{		
		$(el.querySelector('.form_title')).addClass('processing');
	}
	if(!el.dataset.maxHeight){
		el.setAttribute('data-max-height',$(el).css('max-height'));
	}
	if(!$(el).hasClass('active')){
		let elH=el.clientHeight;
		let h=0;
		let delta=0;
		for(let i=0;i<el.childNodes.length;++i){
			if(!el.childNodes[i].tagName) continue;
			h+=el.childNodes[i].clientHeight;
			let mb=$(el.childNodes[i]).css('margin-bottom');
			if(mb){
				mb=parseInt(mb.replace('px',''));
				if(mb>0) h+=mb;
			}
		}
		let fTH=el.querySelector('.form_title').clientHeight;
		delta=elH-fTH;
		el.style.maxHeight=(h+delta).toString()+'px';
	}else{
		el.style.maxHeight=el.dataset.maxHeight;
	}
	el.addEventListener('transitionend',function(){
		$(this).toggleClass('active');
		$(this).removeClass('processing');
		$(this.querySelector('.form_title')).removeClass('processing');
	},{once:true});
}
EDC.prototype.showOrderInfo=function(el,oid){
	this.windowLoading();
	let obj=this;
	$.ajax({
		url: window.location,
		type: 'post',
		data: {'get_order_info': oid},
		success: function (data){
			let res='An error occured. Please try again later.';
			let type='error';
			try{
				data=JSON.parse(data);
				if(data['type']=='success'){					
					if(data['success_text']){ res=data['success_text']; }
					else{ res='Success'; }
					type='success';
				}else{
					if(data['error_text']){	res=data['error_text'];	}
					type='error';
				}
			}catch(ex){}
			if(type=='success'){
				$('#detailed_popup .holder').html(res);				
				$('#detailed_popup').addClass('active');
			}else{
				alert(res);
			}
			obj.windowLoading(false);
		},
		error : function (data){				
			alert(obj.LANG('error','Error'));
			obj.windowLoading(false);
		},
	});
}
EDC.prototype.showOrderPDF=function(el,oid){
	this.windowLoading();
	let obj=this;
	$.ajax({
		url: window.location,
		type: 'post',
		data: {'get_order_pdf': oid},
		success: function (data){
			let res='An error occured. Please try again later.';
			let type='error';
			try{
				data=JSON.parse(data);
				if(data['type']=='success'){					
					if(data['success_text']){ res=data['success_text']; }
					else{ res='Success'; }
					type='success';
				}else{
					if(data['error_text']){	res=data['error_text'];	}
					type='error';
				}
			}catch(ex){}
			if(type=='success'){
				$('#detailed_popup .holder').html(res);				
				$('#detailed_popup').addClass('active');
			}else{
				alert(res);
			}
			obj.windowLoading(false);
		},
		error : function (data){				
			alert(obj.LANG('error','Error'));
			obj.windowLoading(false);
		},
	});
}
EDC.prototype.initTariffPage=function(){
	let cont=document.querySelector('.edc_admin_page.tariffs_page');
	if(!cont) return false;
	this.blocks=cont.querySelectorAll('.edc_tariff_group');
	if(!this.blocks || !this.blocks.length) return false;
	$(this.blocks[0]).addClass('active');
	this.active_block=0;
	for(let i=0;i<this.blocks.length;++i){
		this.blocks[i].addEventListener('mouseenter',(function(edc,ind){ return function(){
			if(edc.blocks[edc.active_block]) $(edc.blocks[edc.active_block]).removeClass('active');
			if(edc.blocks[ind]){
				$(edc.blocks[ind]).addClass('active');
				edc.active_block=ind;
			}
		}})(this,i));
		let fields=this.blocks[i].querySelectorAll('input,select,textarea,radio,checkbox');
		for(let j=0;j<fields.length;++j){
			fields[j].addEventListener('focus',(function(edc,ind){ return function(){
				if(ind!=edc.active_block && edc.blocks[ind]){
					if(edc.blocks[edc.active_block]) $(edc.blocks[edc.active_block]).removeClass('active');				
					$(edc.blocks[ind]).addClass('active');
					edc.active_block=ind;
				}
			}})(this,i));
		}
	}
}
var edc_admin=new EDC();