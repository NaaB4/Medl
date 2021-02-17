
(function(ELEMENT){
	ELEMENT.matches = ELEMENT.matches || ELEMENT.mozMatchesSelector || ELEMENT.msMatchesSelector || ELEMENT.oMatchesSelector || ELEMENT.webkitMatchesSelector;
    ELEMENT.closest = ELEMENT.closest || function closest(selector) {
		if (!this) return null;
		if (this.matches(selector)) return this;
		if (!this.parentElement) {return null}
		else return this.parentElement.closest(selector)
	};
}(Element.prototype));

if(typeof $==='undefined') $=jQuery;
function EDC(){
	const logable=window.location.href.indexOf('.lo')>=0;
	this.is=function(v){
		var res=false;
		if(typeof v==='boolean') res=v;
		else if(typeof v==='string'){
			v=v.toString().trim().toLowerCase();
			res=v=='1' || v=='yes' || v=='true' || v=='on' || v=='y' || v=='ya';
		}
		return res;
	}
	this.isNum=function(str){
		if(typeof str==='undefined' || !str) return false;
		str=str.toString();
		if(str.trim()=='') return false;
		var re= /^[-]?\d*\.?\d*$/;
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
			var $par=$(el).parents('.edc_popup');
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
		var div;
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
		var al=document.body.querySelector('#edc_alert');
		if(al) $(al).removeClass('active');
		$(document.body).removeClass('edc_body_no_scroll');
	}
	this.closeThisAlert=function(ev){
		if(ev && ev.target && $(ev.target).hasClass('edc_popup')) this.closeAlert();
	}
	this.setSetting=function(setting,val){
		this[setting]=val;
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
	var tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
	if(!tabs_holder) return false;
	var tabs=tabs_holder.find('.navigation li');
	var boxes=tabs_holder.find('.items .edc_tab');
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
			var sel=0;
			var obj=this;
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
	var tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
	if(!tabs_holder) return false;
	var tabs=tabs_holder.find('.navigation li');
	tabs.each(function(i){ if(i==n) tabs.get(i).click(); });
}
EDC.prototype.initializeNoTabs=function(cont,n){
	if(n==null) n=0;
	if(cont==null) return false;
	cont=$(cont);
	var tabs_holder=cont.hasClass('edc_tabs')>=0 ? cont : cont.find('.edc_tabs');
	if(!tabs_holder) return false;
	var tabs=tabs_holder.find('.navigation li');
	var boxes=tabs_holder.find('.items .edc_tab');
	if(tabs.length!=boxes.length) return false;
	if(tabs.get(n)==null) n=0;
	$(tabs.get(n)).addClass('active');
	$(boxes.get(n)).addClass('active');
	if($(tabs.get(n)).attr('data-class')) cont.addClass($(tabs.get(n)).attr('data-class'));
}
EDC.prototype.validateForm=function(f){
	var res=true;
	for(var i=0;i<f.length;++i){
		if(!this.fieldValid(f[i])){
			this.setNotValid(f[i]);
			res=false;
		}else{
			this.setValid(f[i]);
		}
	}
	return res;
}
EDC.prototype.fieldValid=function(elem){
	var res=true;
	if(elem.dataset.required){
		if(elem.dataset.required=='numeric'){
			res=this.isNum(elem.value);
		}else if(elem.dataset.required=='date'){
			var parts=(elem.value).trim().split('.');			
			res=parts.length==3 && parts[0]==4 && parts[1]==2 && parts[2]==2;
		}else if(typeof elem.dataset.required==='function'){
			res=elem.dataset.required.call(this,elem,elem.value);
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
EDC.prototype.submit=function(el,params){
	if(!el) return false;
	if($(el).hasClass('loading')) return false;
	var f=params['form'] ? params['form'] : $(el).parents('form').get(0);
	if(!f || !f.length) return false;
	edc.formLoading(f);
	var valid=true;
	valid=this.validateForm(f);
	if(!valid){
		edc.formLoading(f,true);
		return false;
	}
	if(typeof(params)=='undefined') params={};
	if(params['validate'] && typeof this[params['validate']]==='function'){
		valid=this[params['validate']].call(this,f);
	}
	if(!valid){
		edc.formLoading(f,true);
		return false;
	}
	el.addEventListener('click',function(ev){ ev.preventDefault(); });
	var fd = new FormData(f);
	for(var i=0;i<f.length;++i) if(f[i].disabled) fd.append(f[i].name,f[i].value);
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
					var res='An error occured. Try again later.';
					var res_class='error';
					try{
						data=JSON.parse(data);
						if(data['type']=='success'){					
							if(data['success_text']){ res=data['success_text']; }
							else{ res='Success'; }
							res_class='success';
							if(typeof obj[params['callback']]==='function') obj[params['callback']].call(obj,el,data,f);
						}else{
							if(data['error_text']){	res=data['error_text'];	}
							if(typeof res==='object') for(var i=0;i<res.length;++i){
								if(f[res[i].key]){
									if(res[i].type=='alert') obj.alert(res[i].text);
									else obj.setNotValid(f[res[i].key]);
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
				var res='An error occured. Try again later.';
				var res_class='error';
				obj.alert(res);				
				obj.formLoading(f,true);
			},
		});
	})(this);
}
EDC.prototype.simpleSubmit=function(f){
	if(!f) return false;
	for(var i=0;i<f.length;++i) f[i].disabled=false;
	var els=f.querySelectorAll('*[type="submit"]');
	for(var i=0;i<els.length;++i){
		if(els[i].name){
			var el=document.createElement('input');
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
	if(typeof JS_LANG==='undefined' || typeof JS_LAN!=='Object') return def;
	return JS_LANG[code] ? JS_LANG[code] : def;
}
/*EDC.prototype.edcTariffProcess=function(el,data){
	var f=$(el).parents('form');
	f.find('input[name="validate"]').get(0).parentNode.removeChild(f.find('input[name="validate"]').get(0));
	this.simpleSubmit(f.get(0));
}*/
EDC.prototype.formResult=function(f,data){
	if(!f || !data) return;
	var res=f.querySelector('.edc_ajax_result');
	if(!res) return false;
	$(res).addClass('edc_visible');
	if(data.type) $(res).addClass(data.type);
	if(data.text) $(res).html(data.text);
}
EDC.prototype.formLoading=function(f,end){
	if(!f) return false;
	if(!end) end=false;
	var btns=f.querySelectorAll('*[type="submit"]');
	if(btns.length==0) return;
	if(end){
		for(var i=0;i<btns.length;++i){
			$(btns[i]).removeClass('loading');
			btns[i].removeAttribute('disabled');
		}
	}else{
		var res=f.querySelector('.edc_ajax_result');
		if(res){
			var res_classes=['error','special','success','edc_visible'];
			for(var i=0;i<res_classes.length;++i) $(res).removeClass(res_classes[i]);
		}
		for(var i=0;i<btns.length;++i){
			$(btns[i]).addClass('loading');
			$(btns[i]).attr('disabled','disabled');
		}
	}
}
EDC.prototype.getPostalCodesList=function(ev,el){
	if(el.value.length<3) return false;
	var it=setInterval((function(edc,el,len){ return function(){
		if(el.value.length!=len){
		}else{
			var val=el.value;
			var f=el.form ? el.form : $(el).parents('form').get(0);
			var type=f.type.value;
			edc.formLoading(f);
			$.ajax({
				url: window.location,
				type: 'post',
				data: {'edc_get_postcodes_options':val,'type':type},
				success: function (data){
					try{
						data=JSON.parse(data);
						var sel=f.querySelector('select[name="districts"]');
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
	var f=el.form ? el.form : $(el).parents('form').get(0);
	if(!f || !f['annual_consumption']) return false
	f['annual_consumption'].value=el.dataset.value;
	
	var els=f.querySelectorAll('.icons_list .icon');
	for(var i=0;i<els.length;++i){
		if(parseInt(els[i].dataset.value)<=parseInt(f['annual_consumption'].value)){
			$(els[i]).addClass('active');
		}else $(els[i]).removeClass('active');
	}
}
EDC.prototype.submitTariff=function(ev,el,tid){
	ev.preventDefault();
	var f=document.forms['edc_tariff_form'];
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
	var els=document.body.querySelectorAll('.edc_checkbox');
	for(var i=0;i<els.length;++i) els[i].addEventListener('click',function(ev){
		if(ev.target && ev.target.tagName && ev.target.tagName.toLowerCase()=='a') return;
		$(this).toggleClass('active');
	});
}
EDC.prototype.initRadios=function(){
	var els=document.body.querySelectorAll('.edc_radio');
	for(var i=0;i<els.length;++i){
		els[i].addEventListener('click',function(ev){
			if(ev.target && ev.target.tagName && ev.target.tagName.toLowerCase()=='a') return;
			var r=this.querySelector('input[type="radio"]');
			if(r){
				var f=this.closest('form');
				var rs=f.querySelectorAll('input[type="radio"][name="'+r.name+'"]');
				for(var j=0;j<rs.length;++j){
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
	var els=document.body.querySelectorAll('.edc *[data-required]');
	for(var i=0;i<els.length;++i){
		els[i].addEventListener('focus',function(){
			$(this).removeClass('required');
			$(this.parentNode).removeClass('required');
			$(this).parents('.col_2').removeClass('required');
		});
	}
	els=document.body.querySelectorAll('.edc .required_text');
	for(var i=0;i<els.length;++i){
		els[i].addEventListener('click',function(){
			var el=this.parentNode.querySelector('*[data-required]');
			if(el) el.focus();
		});
	}
}
EDC.prototype.numericalFields=function(){	
	var els=document.body.querySelectorAll('.edc input.only_numeric');
	for(var i=0;i<els.length;++i){
		$(els[i]).attr('autocomplete','off');
		els[i].addEventListener('keydown',(function(edc){ return function(ev){
			var available=[8,13,27];
			var s=String.fromCharCode(ev.keyCode);
			if(!edc.isNum(s) && available.indexOf(ev.keyCode)==-1){
				ev.preventDefault();
			}
		}})(this));
	}
}
EDC.prototype.jumpingFields=function(){	
	var els=document.body.querySelectorAll('.jumping_wrapper > input');
	for(var i=0;i<els.length;++i){
		$(els[i]).attr('autocomplete','off');
		els[i].addEventListener('keypress',(function(edc){ return function(ev){
			var avaiable=[8,13,27];
			var s=String.fromCharCode(ev.keyCode);
			if(avaiable.indexOf(ev.keyCode)==-1 && this.nextElementSibling){
				this.nextElementSibling.focus();
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
	var f=document.forms['edc_order_form'];
	if(!f) return false;
	var debit=f.querySelector('input[name="edc_sepa_direct_debit"]');
	var label_debit;
	if(debit.id){
		label_debit=f.querySelector('label[for="'+debit.id+'"');
	}
	var transfer=f.querySelector('input[name="edc_transfer"]');
	var label_transfer;
	if(transfer.id){
		label_transfer=f.querySelector('label[for="'+transfer.id+'"');
	}
	var d_form=f.querySelector('#direct_debit_form');
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
	var other=f.querySelector('input[name="edc_other_debit"]');
	var other_form=f.querySelector('#etc_fields');
	if(other && other_form){		
		other.addEventListener('change',function(){
			var fields=['edc_etc_gender','edc_etc_firstname','edc_etc_name','edc_etc_street','edc_etc_house','edc_etc_zip','edc_etc_city'];
			if(this.checked){
				$(other_form).addClass('active');
				for(var i=0;i<fields.length;++i) if(f[fields[i]]){
					f[fields[i]].setAttribute('data-required','1');
				}
			}else{
				$(other_form).removeClass('active');
				for(var i=0;i<fields.length;++i) if(f[fields[i]]){
					f[fields[i]].removeAttribute('data-required');
				}
			}
		});
	}
}
EDC.prototype.calculateAge=function(date){
    var today=new Date();
    var birthDate=new Date(date);
	console.log(today);
	console.log(birthDate);
    var age=today.getFullYear()-birthDate.getFullYear();
    var m=today.getMonth()-birthDate.getMonth();
    if (m<0 || (m===0 && today.getDate()<birthDate.getDate())){
        age--;
    }
    return age;
}
EDC.prototype.orderStepValidate=function(f){
	var birth=f['edc_date_of_birth'].value;
	birth=birth.split('.');
	birth=birth.reverse();
	birth=birth.join('-');
	var age=this.calculateAge(birth);
	if(age<18){ this.setNotValid(f['edc_date_of_birth']); return false; }
	if(!f || !f['confirmation'] || !document.body.querySelector('#edc_email_confirmation')) return true;
	if(!this.use_email_confirmation) return true;
	if(f['confirmation'].value!='') return true;
	this.sendConfirmationCode(f);
	return false;
}
EDC.prototype.sendConfirmationCode=function(f){
	if(!this.use_email_confirmation) return;
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
				var res='An error occured. Try again later.';
				obj.alert(res);
				edc.formLoading(f,true);
			},
		});
	}(this));
}
EDC.prototype.confirmationCode=function(el,data,f){
	var f2=document.forms['edc_order_form'];
	if(f2 && f2['confirmation']) f2['confirmation'].value=f['confirmation_code'].value;
	edc.popup('#edc_email_confirmation',true);
	f2.querySelector('button[type="submit"]').click();
}
EDC.prototype.startRepeatTimer=function(el,max){
	if(!el) return false;
	if(!max) max=60;
	if(el.querySelector('a')) el.removeChild(el.querySelector('a'));
	if(!el.querySelector('span.count')){
		var span=document.createElement('span');
		span.className='count';
		el.appendChild(span);
	}
	el.querySelector('span.count').style.display='inline-block';
	el.querySelector('span.count').innerHTML=max;
	var it=setInterval((function(obj,el,i){ return function(){
		--i;
		el.querySelector('span.count').innerHTML=i;
		if(i<=0){
			var a=document.createElement('a');
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
var edc=new EDC();
window.addEventListener('load',(function(edc){ return function(){
	edc.initCheckboxes();
	edc.initRadios();
	edc.requiredFields();
	edc.numericalFields();
	edc.jumpingFields();
	edc.datepickerFields();
	edc.formDefaultActions();
}})(edc));