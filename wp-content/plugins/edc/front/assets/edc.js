if(typeof $==='undefined') $=jQuery;
function EDC(){
	const logable=window.location.href.indexOf('.lo')>=0;
	this.settings={};
	this.is=function(v){
		let res=false;
		if(typeof v==='boolean') res=v;
		else if(typeof v==='string'){
			v=v.toString().trim().toLowerCase();
			res=v=='1' || v=='yes' || v=='true' || v=='on' || v=='y' || v=='ya';
		}else if(typeof v==='number'){
			res=v===1;
		}
		return res;
	}
	this.isNum=function(str){
		if(typeof str==='undefined' || !str) return false;
		str=str.toString();
		if(str.trim()=='') return false;
		let re= /^[-]?\d*\.?\d*$/;
		if (!str.match(re)) return false;
		return true;
	}
	this.popup=function(el,hide){
		if(!hide) hide=false;
		if(!el) return false;
		if(typeof el=='string'){
			el=document.body.querySelector(el);
		}
		if(!$(el).hasClass('edc_popup')){
			let $par=$(el).parents('.edc_popup');
			if($par.length>0) el=$par.get(0);
			else{
				el=$(el).find('.edc_popup').get(0);
			}
		}
		if(!el) return;
		if(hide){
			$(el).removeClass('active');
			$(document.body).removeClass('edc_body_no_scroll');
		}else{
			$(el).addClass('active');
			$(document.body).addClass('edc_body_no_scroll');
		}
		return true;
	}
	this.alert=function(txt){
		let div;
		if(!document.body.querySelector('#edc_alert')){
			div=document.createElement('div');
			div.id='edc_alert';
			div.className='edc_popup';
			div.innerHTML='<div class="edc edc_popup_wrapper">\
				<div class="edc_close">&times;</div>\
				<div class="edc_popup_content"></div>\
				<button type="button" class="btn">OK</button>\
			</div>';
			document.body.appendChild(div);
		}else div=document.body.querySelector('#edc_alert');
		div.addEventListener('click',(function(obj){return function(ev){ obj.closeThisAlert(ev); }})(this));
		div.querySelector('.edc_close').addEventListener('click',(function(obj){return function(){ obj.closeAlert(); }})(this));
		div.querySelector('.btn').addEventListener('click',(function(obj){return function(){ obj.closeAlert(); }})(this));
		div.querySelector('.edc_popup_content').innerHTML=txt;
		$(div).addClass('active');
		$(document.body).addClass('edc_body_no_scroll');
	}
	this.closeAlert=function(){
		let al=document.body.querySelector('#edc_alert');
		if(al) $(al).removeClass('active');
		$(document.body).removeClass('edc_body_no_scroll');
	}
	this.closeThisAlert=function(ev){
		if(ev && ev.target && $(ev.target).hasClass('edc_popup')) this.closeAlert();
	}
	this.setSetting=function(setting,val){
		this.settings[setting]=val;
	}
	this.init=function(){
		window.addEventListener('load',(function(obj){ return function(){
			
		}})(this));
	}
	this.log=function(txt,ex){
		if(!logable) return;
		console.log(txt);
		if(ex) console.log('Exception: '+ex.toString());
	}
	this.init();
}
EDC.prototype.initializeTabs=function(container,n){
	if(n==null) n=0;
	if(container==null) return false;
	container=$(container);
	let tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
	if(!tabs_holder) return false;
	let tabs=tabs_holder.find('.navigation li');
	let boxes=tabs_holder.find('.items .edc_tab');
	if(tabs.length!=boxes.length) return false;
	if(tabs.get(n)==null) n=0;
	$(tabs.get(n)).addClass('active');
	$(boxes.get(n)).addClass('active');
	if($(tabs.get(n)).attr('data-class')) container.addClass($(tabs.get(n)).attr('data-class'));
	tabs.each(function(i){
		$(this).find('a').on('click',function(e){ e.preventDefault(); });
		if(i!=n){ $(boxes.get(i)).removeClass('active'); }
		$(this).on('click',(function(c,t,b){ return function(){
			if($(this).hasClass('active')) return false;
			let sel=0;
			let obj=this;
			t.each(function(index){
				$(t.get(index)).removeClass('active');
				$(b.get(index)).removeClass('active');
				if($(t.get(index)).attr('data-class')) c.removeClass($(t.get(index)).attr('data-class'));
				if(t.get(index)===obj) sel=index;
			});
			$(t.get(sel)).addClass('active');
			$(b.get(sel)).addClass('active');
			if($(t.get(sel)).attr('data-class')) c.addClass($(t.get(sel)).attr('data-class'));
		}})(container,tabs,boxes));
	});
}
EDC.prototype.setTab=function(container,n){
	if(n==null) n=0;
	if(!container) return false;
	container=$(container);
	let tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
	if(!tabs_holder) return false;
	let tabs=tabs_holder.find('.navigation li');
	tabs.each(function(i){ if(i==n) tabs.get(i).click(); });
}
EDC.prototype.initializeNoTabs=function(cont,n){
	if(n==null) n=0;
	if(cont==null) return false;
	cont=$(cont);
	let tabs_holder=cont.hasClass('edc_tabs')>=0 ? cont : cont.find('.edc_tabs');
	if(!tabs_holder) return false;
	let tabs=tabs_holder.find('.navigation li');
	let boxes=tabs_holder.find('.items .edc_tab');
	if(tabs.length!=boxes.length) return false;
	if(tabs.get(n)==null) n=0;
	$(tabs.get(n)).addClass('active');
	$(boxes.get(n)).addClass('active');
	if($(tabs.get(n)).attr('data-class')) cont.addClass($(tabs.get(n)).attr('data-class'));
}
EDC.prototype.validateForm=function(f){
	let res=true;
	let first=true;
	for(let i=0;i<f.length;++i){
		if(!this.fieldValid(f[i])){
			if(first) this.scrollToEl(f[i]);
			this.setNotValid(f[i]);
			res=false;
			first=false;
		}else{
			this.setValid(f[i]);
		}
	}
	return res;
}
EDC.prototype.fieldValid=function(elem){
	let res=true;
	if(elem.dataset.required){
		if(elem.dataset.required=='numeric'){
			res=this.isNum(elem.value);
		}else if(elem.dataset.required=='date'){
			let parts=(elem.value).trim().split('.');			
			res=parts.length==3 && parts[0]==4 && parts[1]==2 && parts[2]==2;
		}else if(typeof elem.dataset.required==='function'){
			res=elem.dataset.required.call(this,elem,elem.value);
		}else if(elem.dataset.required=='age'){			
			let birth=elem.value;
			if(birth.trim()==''){ res=false; }
			else{
				birth=birth.split('.');
				birth=birth.reverse();
				birth=birth.join('-');
				let age=this.calculateAge(birth);
				if(!age || age<18){ res=false; }
			}
		}else{
			res=elem.type=='checkbox' ? elem.checked : !(elem.value.trim()=='');
		} 
	}
	return res;
}
EDC.prototype.setNotValid=function(elem){
	if(!elem) return false;
	$(elem).addClass('required');
	$(elem.parentNode).addClass('required');
	$(elem).parents('.col_2').addClass('required');
}
EDC.prototype.setValid=function(elem){
	if(!elem) return false;
	$(elem).removeClass('required');
	$(elem.parentNode).removeClass('required');
	$(elem).parents('.col_2').removeClass('required');
}
EDC.prototype.recaptchaValidate=function(f,cbf,cb){
	if(typeof this.settings.recaptcha_key=='undefined' || this.recaptcha_key=='' || typeof grecaptcha=='undefined'){
		cb.call(this);
		return;
	}
	let el;
	if(!f.querySelector('input[name="recaptcha_response"]')){
		el=document.createElement('input');
		el.type='hidden';
		el.name='recaptcha_response';
		f.appendChild(el);
	}else el=f.querySelector('input[name="recaptcha_response"]');	
	if(this.settings.recaptcha_version=='2.0'){
		let rc=f.querySelector('.recaptcha_holder .recaptcha');
		if(!rc) return  cb.call(this);
		if(typeof this.recaptchas[rc.id]=='undefined') return  cb.call(this);
		let resp=grecaptcha.getResponse(this.recaptchas[rc.id].widget_id);	
		el.value=resp;
		if(el.value==''){
			cbf.call(this);
		}else{			
			cb.call(this);
		}
	}else if(this.settings.recaptcha_version=='3.0'){
		(function(obj){
			grecaptcha.execute(obj.settings.recaptcha_key, {action: 'edc_page'}).then(function(token){
				el.value=token;
				if(el.value==''){
					cbf.call(obj);
				}else{			
					cb.call(obj);
				}
			});
		}(this));
	}
}
EDC.prototype.submit=function(el,params){
	if(!el) return false;
	if($(el).hasClass('loading')) return false;
	let f=params['form'] ? params['form'] : $(el).parents('form').get(0);
	if(!f || !f.length) return false;
	edc.formLoading(f);
	let valid=true;
	valid=this.validateForm(f);
	if(!valid){
		edc.formLoading(f,true);
		return false;
	}
	this.recaptchaValidate(f,function(){
		edc.formLoading(f,true);
		this.alert(this.LANG('edc_recaptcha_error','You must to prove that you are not a robot'));
		return false;
	},function(){
		if(typeof(params)=='undefined') params={};
		if(params['validate'] && typeof this[params['validate']]==='function'){
			valid=this[params['validate']].call(this,f);
		}
		if(!valid){
			edc.formLoading(f,true);
			return false;
		}
		el.addEventListener('click',function(ev){ ev.preventDefault(); });
		let fd = new FormData(f);
		for(let i=0;i<f.length;++i) if(f[i].disabled) fd.append(f[i].name,f[i].value);
		console.log(fd);
		(function(obj){
			$.ajax({
				url: window.location,
				type: 'post',
				data: fd,
				processData: false,
				contentType: false,
				success: function (data){
					obj.log(data);
					if(typeof obj[params['own_callback']]==='function'){				
						if(typeof obj[params['own_callback']]==='function') obj[params['own_callback']].call(obj,el,data);
					}else{
						let res='An error occured. Try again later.';
						let res_class='error';
						try{
							data=JSON.parse(data);
							if(data['type']=='success'){
								if(data['success_text']){ res=data['success_text']; }
								else{ res='Success'; }
								res_class='success';
								if(typeof obj[params['callback']]==='function') obj[params['callback']].call(obj,el,data,f);								
								if(data.text || data.success_text){
									if(!data.text) data.text=data.success_text;
									edc.formResult(f,data);
								}
							}else{
								if(data['error_text']){	res=data['error_text'];	}
								if(typeof res==='object'){
									let first=true;
									for(let i=0;i<res.length;++i){
										if(f[res[i].key]){
											if(res[i].type=='alert') obj.alert(res[i].text);
											else{
												if(first) obj.scrollToEl(f[res[i].key]);
												obj.setNotValid(f[res[i].key]);
												first=false;
											}
										}
									}
								}else{
									if(data.text || data.error_text){
										if(!data.text) data.text=data.error_text;
										edc.formResult(f,data);
									}
								}
								res_class='error';
								if(typeof obj[params['ecallback']]==='function') obj[params['ecallback']].call(obj);
							}
						}catch(ex){}
						//if(res_class=='error' || params['alert']===true) obj.alert(res);
						edc.formLoading(f,true);
					}
				},
				error: function(data){
					obj.log(data);
					let res='An error occured. Try again later.';
					let res_class='error';
					obj.alert(res);				
					obj.formLoading(f,true);
				},
			});
		})(this);
	});	
}
EDC.prototype.simpleSubmit=function(f){
	this.formLoading(f);
	if(!f) return false;
	for(let i=0;i<f.length;++i) f[i].disabled=false;
	let els=f.querySelectorAll('*[type="submit"]');
	for(let i=0;i<els.length;++i){
		if(els[i].name){
			let el=document.createElement('input');
			el.type='hidden';
			el.name=els[i].name;
			el.value=els[i].value;
			f.appendChild(el);
		}
	}
	f.submit();
}
EDC.prototype.LANG=function(code,def){
	if(typeof def==='undefined') def='';
	if(typeof EDC_JS_LANG==='undefined' || typeof EDC_JS_LANG!=='object') return def;
	return EDC_JS_LANG[code] ? EDC_JS_LANG[code] : def;
}
EDC.prototype.formResult=function(f,data){
	if(!f || !data) return;
	let res=f.querySelector('.edc_ajax_result');
	if(!res) return false;
	$(res).addClass('edc_visible');
	if(data.type) $(res).addClass(data.type);
	if(data.text) $(res).html(data.text);
}
EDC.prototype.formLoading=function(f,end){
	if(!f) return false;
	if(!end) end=false;
	let btns=f.querySelectorAll('*[type="submit"]');
	if(btns.length==0) return;
	if(end){
		for(let i=0;i<btns.length;++i){
			$(btns[i]).removeClass('loading');
			btns[i].removeAttribute('disabled');
		}
	}else{
		let res=f.querySelector('.edc_ajax_result');
		if(res){
			let res_classes=['error','special','success','edc_visible'];
			for(let i=0;i<res_classes.length;++i) $(res).removeClass(res_classes[i]);
		}
		for(let i=0;i<btns.length;++i){
			$(btns[i]).addClass('loading');
			$(btns[i]).attr('disabled','disabled');
		}
	}
}
EDC.prototype.getPostalCodesList=function(ev,el){
	if(el.value.length<3) return false;
	let it=setInterval((function(edc,el,len){ return function(){
		if(el.value.length!=len){
		}else{
			let val=el.value;
			let f=el.form ? el.form : $(el).parents('form').get(0);
			let type=f.type.value;
			edc.formLoading(f);
			$.ajax({
				url: window.location,
				type: 'post',
				data: {'edc_get_postcodes_options':val,'type':type},
				success: function (data){
					try{
						data=JSON.parse(data);
						let sel=f.querySelector('select[name="districts"]');
						if(!sel) return false;
						if(data.type=='success'){
							sel.innerHTML=data.text;
						}else{
							edc.formResult(f,data);
						}
						edc.formLoading(f,true);
					}catch(ex){ edc.log('Error with postcodes result',ex); }
				},
				error: function(data){},
			});	
		}
		clearInterval(it);
	}})(this,el,el.value.length),500);
}
EDC.prototype.setAnnual=function(ev,el){	
	if(!el) return false;
	let f=el.form ? el.form : $(el).parents('form').get(0);
	if(!f || !f['annual_consumption'] || el.dataset.value=='') return false
	f['annual_consumption'].value=el.dataset.value;
	
	let els=f.querySelectorAll('.icons_list .icon');
	for(let i=0;i<els.length;++i){
		if(parseInt(els[i].dataset.value)<=parseInt(f['annual_consumption'].value)){
			$(els[i]).addClass('active');
		}else $(els[i]).removeClass('active');
	}
}
EDC.prototype.submitTariff=function(ev,el,tid){
	ev.preventDefault();
	let f=document.forms['edc_tariff_form'];
	if(!this.isNum(tid) || $(el).hasClass('loading') || !f) return false;
	f.id_tariff.value=tid;
	this.submit(el,{
		'form' : f,
		'callback' : 'afterStepProcess',
	});
}
EDC.prototype.afterStepProcess=function(el,data,f){
	if(!f) f=$(el).parents('form').get(0);
	$(f).find('input[name="validate"]').get(0).parentNode.removeChild($(f).find('input[name="validate"]').get(0));
	this.simpleSubmit(f);	
}
EDC.prototype.initCheckboxes=function(){
	let els=document.body.querySelectorAll('.edc_checkbox');
	for(let i=0;i<els.length;++i) els[i].addEventListener('click',function(ev){
		if(ev.target && ev.target.tagName && ev.target.tagName.toLowerCase()=='a') return;
		$(this).toggleClass('active');
	});
}
EDC.prototype.initRadios=function(){
	let els=document.body.querySelectorAll('.edc_radio');
	for(let i=0;i<els.length;++i){
		els[i].addEventListener('click',function(ev){
			if(ev.target && ev.target.tagName && ev.target.tagName.toLowerCase()=='a') return;
			let r=this.querySelector('input[type="radio"]');
			if(r){
				let f=this.closest('form');
				let rs=f.querySelectorAll('input[type="radio"][name="'+r.name+'"]');
				for(let j=0;j<rs.length;++j){
					if(rs[j]!=this){
						//console.log();
						$(rs[j].closest('.edc_radio')).removeClass('active');
					}
				}
			}
			$(this).addClass('active');
		});
		els[i].querySelector('input[type="radio"]').addEventListener('click',function(ev){
			ev.stopPropagation();
		});
	}
}
EDC.prototype.requiredFields=function(){
	let els=document.body.querySelectorAll('.edc *[data-required]');
	for(let i=0;i<els.length;++i){
		els[i].addEventListener('focus',function(){
			$(this).removeClass('required');
			$(this.parentNode).removeClass('required');
			$(this).parents('.col_2').removeClass('required');
		});
	}
	els=document.body.querySelectorAll('.edc .required_text');
	for(let i=0;i<els.length;++i){
		els[i].addEventListener('click',function(){
			let el=this.parentNode.querySelector('*[data-required]');
			if(el) el.focus();
		});
	}
}
EDC.prototype.numericalFields=function(){	
	let els=document.body.querySelectorAll('.edc input.only_numeric');
	for(let i=0;i<els.length;++i){
		$(els[i]).attr('autocomplete','off');
		els[i].addEventListener('keydown',(function(edc){ return function(ev){
			let available=[8,9,13,27,96,97,98,99,100,101,102,103,104,105,116];
			let s=String.fromCharCode(ev.keyCode);
			if(!edc.isNum(s) && available.indexOf(ev.keyCode)==-1){
				ev.preventDefault();
			}
		}})(this));
	}
}
EDC.prototype.jumpingFields=function(){	
	let els=document.body.querySelectorAll('.jumping_wrapper > input');
	for(let i=0;i<els.length;++i){
		$(els[i]).attr('autocomplete','off');
		els[i].addEventListener('keypress',(function(edc){ return function(ev){
			let avaiable=[8,13,27];
			let s=String.fromCharCode(ev.keyCode);
			if(avaiable.indexOf(ev.keyCode)==-1 && this.nextElementSibling){
				this.nextElementSibling.focus();
			}
		}})(this));
	}
	els=document.body.querySelectorAll('.jumping_wrapper');
	for(let i=0;i<els.length;++i){
		els[i].addEventListener('paste',(function(edc){ return function(ev){
			let val=(event.clipboardData || window.clipboardData).getData('text');
			let els=this.querySelectorAll('input');
			for(let j=0;j<val.length;++j){
				if(els[j]) els[j].value=val[j];
			}
		}})(this));
	}
}
EDC.prototype.datepickerFields=function(){	
	try{
		$(".datepicker").datepicker({
			prevText: '&#x3c;zurück', prevStatus: '',
			prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
			nextText: 'Vor&#x3e;', nextStatus: '',
			nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
			currentText: 'heute', currentStatus: '',
			todayText: 'heute', todayStatus: '',
			clearText: '-', clearStatus: '',
			closeText: 'schließen', closeStatus: '',
			monthNames: ['Januar','Februar','März','April','Mai','Juni', 'Juli','August','September','Oktober','November','Dezember'],
			monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun', 'Jul','Aug','Sep','Okt','Nov','Dez'],
			dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
			dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
			dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
			dateFormat : 'dd.mm.yy',
		});
		$(".datepicker").attr('autocomplete','off');
	}catch(ex){ this.log('Datepicker error',ex); }
}
EDC.prototype.formDefaultActions=function(){
	let f=document.forms['edc_order_form'];
	if(!f) return false;
	let debit=f.querySelector('input[name="edc_sepa_direct_debit"]');
	let label_debit;
	if(debit.id){
		label_debit=f.querySelector('label[for="'+debit.id+'"');
	}
	let transfer=f.querySelector('input[name="edc_transfer"]');
	let label_transfer;
	if(transfer.id){
		label_transfer=f.querySelector('label[for="'+transfer.id+'"');
	}
	let d_form=f.querySelector('#direct_debit_form');
	if(debit){
		debit.addEventListener('change',function(){
			if(this.checked){
				if(d_form) $(d_form).addClass('active');
				if(transfer){
					transfer.checked=false;
					if(label_transfer) $(label_transfer).removeClass('active');
				}
			}else{
				if(d_form) $(d_form).removeClass('active');
			}
		});
	}
	if(transfer){
		transfer.addEventListener('change',function(){
			if(this.checked){
				if(d_form) $(d_form).removeClass('active');
				if(debit){
					debit.checked=false;
					if(label_debit) $(label_debit).removeClass('active');
				}
			}
		});
	}
	let other=f.querySelector('input[name="edc_other_debit"]');
	let other_form=f.querySelector('#etc_fields');
	if(other && other_form){
		other.addEventListener('change',function(){
			let fields=['edc_etc_gender','edc_etc_firstname','edc_etc_name','edc_etc_street','edc_etc_house','edc_etc_zip','edc_etc_city'];
			if(this.checked){
				$(other_form).addClass('active');
				for(let i=0;i<fields.length;++i) if(f[fields[i]]){
					f[fields[i]].setAttribute('data-required','1');
				}
			}else{
				$(other_form).removeClass('active');
				for(let i=0;i<fields.length;++i) if(f[fields[i]]){
					f[fields[i]].removeAttribute('data-required');
				}
			}
		});
	}
}
EDC.prototype.calculateAge=function(date){
    let today=new Date();
    let birthDate=new Date(date);
    let age=today.getFullYear()-birthDate.getFullYear();
    let m=today.getMonth()-birthDate.getMonth();
    if (m<0 || (m===0 && today.getDate()<birthDate.getDate())){
        age--;
    }
    return age;
}
EDC.prototype.orderStepValidate=function(f){
	let birth=f['edc_date_of_birth'].value;
	if(birth.trim()==''){ this.setNotValid(f['edc_date_of_birth']); return false; }
	birth=birth.split('.');
	birth=birth.reverse();
	birth=birth.join('-');
	let age=this.calculateAge(birth);
	if(!age || age<18){ this.setNotValid(f['edc_date_of_birth']); return false; }
	if(!f || !f['confirmation'] || !document.body.querySelector('#edc_email_confirmation')) return true;
	if(!this.settings.use_email_confirmation) return true;
	if(f['confirmation'].value!='') return true;
	this.sendConfirmationCode(f);
	return false;
}
EDC.prototype.sendConfirmationCode=function(f){
	if(!this.settings.use_email_confirmation) return;
	(function(obj){
		edc.formLoading(f);
		$.ajax({
			url: window.location,
			type: 'post',
			data: {'send_confirmation_code':1,'email':f['edc_email'].value},
			success: function (data){
				try{
					data=JSON.parse(data);
					obj.popup('#edc_email_confirmation');
					obj.startRepeatTimer(document.body.querySelector('#edc_email_confirmation .repeat_confirmation_code'));
				}catch(ex){ obj.log('confirmation error',ex); }
				edc.formLoading(f,true);
			},
			error: function(data){
				obj.log(data);
				let res='An error occured. Try again later.';
				obj.alert(res);
				edc.formLoading(f,true);
			},
		});
	}(this));
}
EDC.prototype.confirmationCode=function(el,data,f){
	let f2=document.forms['edc_order_form'];
	if(f2 && f2['confirmation']) f2['confirmation'].value=f['confirmation_code'].value;
	edc.popup('#edc_email_confirmation',true);
	f2.querySelector('button[type="submit"]').click();
}
EDC.prototype.startRepeatTimer=function(el,max){
	if(!el) return false;
	if(!max) max=60;
	if(el.querySelector('a')) el.removeChild(el.querySelector('a'));
	if(!el.querySelector('span.count')){
		let span=document.createElement('span');
		span.className='count';
		el.appendChild(span);
	}
	el.querySelector('span.count').style.display='inline-block';
	el.querySelector('span.count').innerHTML=max;
	let it=setInterval((function(obj,el,i){ return function(){
		--i;
		el.querySelector('span.count').innerHTML=i;
		if(i<=0){
			let a=document.createElement('a');
			a.innerHTML=el.dataset.text ? el.dataset.text : 'Click';
			a.addEventListener('click',function(){
				obj.sendConfirmationCode(document.forms['edc_order_form']);
				obj.startRepeatTimer(el,max);
			});
			a.href='javascript:void(0);';
			el.appendChild(a);
			el.querySelector('span.count').style.display='none';
			clearInterval(it);
		}
	}})(this,el,max),1000);
}
EDC.prototype.recaptchaIsReady=function(cb){
	if(typeof cb!='function') return;
	let it=setInterval((function(i){ return function(){
		if(++i>1000 || typeof grecaptcha!='undefined'){
			clearInterval(it);
			if(typeof grecaptcha!='undefined') cb.call();
		}
	}})(0),100);
}
EDC.prototype.initRecaptcha=function(){
	if(typeof this.recaptchas=='undefined') this.recaptchas={};
	this.recaptchaIsReady((function(obj){ return function(){
		if(obj.settings.recaptcha_version=='2.0'){
			if(typeof obj.settings.recaptcha_key=='undefined' || obj.settings.recaptcha_key=='') return '';
			let els=document.body.querySelectorAll('.recaptcha_holder:not([data-initialized]) .recaptcha');
			for(let i=0;i<els.length;++i){
				obj.recaptchas[els[i].id]={
					widget_id : grecaptcha.render(els[i].id, {
								  'sitekey' : obj.settings.recaptcha_key,
								  'theme' : 'light',
								}),
					el : els[i],
				};
				els[i].parentNode.setAttribute('data-initialized','1');
			}
		}
		
	}})(this));
}
EDC.prototype.initTariffPopup=function(){
	let el=document.body.querySelector('#edc_tariff_popup');
	if(!el) return;
	document.body.appendChild(el);
}
EDC.prototype.scrollToEl=function(el){
	if(!el) return false;	
	this.smoothScroll(0,$(el).offset().top-40,10);
}
EDC.prototype.getClientSTop=function(){ return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop); }
EDC.prototype.getClientSLeft=function(){ return self.pageXOffset || (document.documentElement && document.documentElement.scrollLeft) || (document.body && document.body.scrollLeft); }
EDC.prototype.smoothScroll=function(x,y,it,delay){
	if(it==null) it=20;
	if(delay==null) delay=1;
	cur_x=this.getClientSLeft();
	cur_y=this.getClientSTop();
	let dir_y=1;
	let dir_x=1;
	if(y>cur_y) dir_y=2;
	if(x>cur_x) dir_x=2;
	let i=setInterval(function(){
		if(cur_x>x && dir_x==1) cur_x-=it;
		else if(cur_x<x && dir_x==2) cur_x+=it;
		if(cur_y>y && dir_y==1) cur_y-=it;
		else if(cur_y<y && dir_y==2) cur_y+=it;
		window.scrollTo(cur_x,cur_y);
		if((dir_x==1 && cur_x<=x || dir_x==2 && cur_x>=x) && (dir_y==1 && cur_y<=y || dir_y==2 && cur_y>=y)){
			window.scrollTo(x,y);
			clearInterval(i);
		}
	},delay);
}
var edc=new EDC();
window.addEventListener('load',(function(edc){ return function(){
	edc.initCheckboxes();
	edc.initRadios();
	edc.requiredFields();
	edc.numericalFields();
	edc.jumpingFields();
	edc.datepickerFields();
	edc.formDefaultActions();
	edc.initRecaptcha();
	edc.initTariffPopup();
}})(edc));