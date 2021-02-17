(function(w,edc,edcp){
	edcp.addConsumption=function(el,val){
		let par=el.closest('.edc_form_row');
		if(!par) return false;
		let inp=par.querySelector('input[type="text"]');
		if(!inp) return false;
		let curr=inp.value;
		if(curr=='') curr=0;
		//this.log(curr);
		if(curr!==0 && !this.isNum(curr)) return false;
		curr=parseFloat(curr);
		let n=curr+val;
		if(n<=0) n=0;
		inp.value=n;
	}	
	edcp.initializeTabs=function(container,n){
		//return;
		if(container==null) return false;
		container=$(container);
		let tabs_holder=container.hasClass('edc_tabs')>=0 ? container : container.find('.edc_tabs');
		if(!tabs_holder) return false;
		let tabs=tabs_holder.find('.navigation li');
		let boxes=tabs_holder.find('.items .edc_tab');
		if(tabs.length!=boxes.length) return false;
		if(n==null){
			n=0;
		}else{			
			//$(tabs.get(n)).addClass('active');
			//$(boxes.get(n)).addClass('active');
			if($(tabs.get(n)).attr('data-class')) container.addClass($(tabs.get(n)).attr('data-class'));
		}
		if(tabs.get(n)==null) n=0;
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
	edcp.setAnnualCombi=function(ev,el){	
		if(!el) return false;
		let par=$(el).parents('.edc_form_row').get(0);
		if(!par) return false;
		let field=par.querySelector('input[name^="annual_consumption"]');
		if(!field) return false
		field.value=el.dataset.value;
	}
	edcp.showTariffsPopup=function(cons,type,ids,young){
		let cont=document.body.querySelector('#edc_tariffs_popup');
		if(!cont) return false;
		let classes=['electricity','gas','combi'];
		for(let i=0;i<classes.length;++i) $(cont).removeClass('theme_'+classes[i]);
		$(cont).addClass('theme_'+type);
		if(type!='combi'){
			let els_cons=cont.querySelectorAll('input[name="annual_consumption"]');
			for(let i=0;i<els_cons.length;++i){
				els_cons[i].value=cons;
			}
		}else{
			let els_cons1=cont.querySelectorAll('input[name="annual_consumption_el"]');
			for(let i=0;i<els_cons1.length;++i){
				els_cons1[i].value=cons[0];
			}
			let els_cons2=cont.querySelectorAll('input[name="annual_consumption_gas"]');
			for(let i=0;i<els_cons2.length;++i){
				els_cons2[i].value=cons[1];
			}
			
		}
		let els_ids=cont.querySelectorAll('input[name="tariff_ids"]');
		for(let i=0;i<els_ids.length;++i){
			els_ids[i].value=ids;
		}
		let els_young=cont.querySelectorAll('.young');
		for(let i=0;i<els_young.length;++i){
			els_young[i].style.display=this.is(young) ? 'block' : 'none';
		}
		this.popup('#edc_tariffs_popup');
	}
	edcp.initCheckboxes=function(){
		return;
		let els=document.body.querySelectorAll('.edc_checkbox');
		for(let i=0;i<els.length;++i) els[i].addEventListener('click',function(ev){
			if(ev.target && ev.target.tagName && ev.target.tagName.toLowerCase()=='a') return;
			$(this).toggleClass('active');
		});
	}
	edcp.initRadios=function(){
		return;
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
	edcp.setNotValid=function(elem,txt){
		if(!elem) return false;
		$(elem).addClass('required');
		$(elem.parentNode).addClass('required');
		$(elem).parents('.col_2').addClass('required');
		if(typeof txt!=='undefined' && txt){
			if(elem.parentNode.querySelector('.required_text')) elem.parentNode.querySelector('.required_text').innerHTML=txt;
		}
	}
	edcp.orderStepValidate=function(f){
		//if(!el) return false;
	//	var f=el.closest('form');
		if(!f) return false;
		if(f.tagName.toLowerCase()!='form' ) f=f.closest('form');
		valid=this.validateForm(f);
		let birth=f['edc_date_of_birth'].value;
		birth=birth.split('.');
		birth=birth.reverse();
		birth=birth.join('-');
		let age=this.calculateAge(birth);
		let sd=JSON.parse(f['steps_data'].value);
		if(birth==''){
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Geburtsdatum auswählen');
			valid=false;
		}
		if(age<18){			
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Du musst mindestens 18 Jahre alt sein');
			valid=false;
		}
		if(this.is(sd.first.lower_30) && age>30){
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Du darfst für diesen Tarif nicht über 30 Jahre alt sein');
			valid=false;
		}
		if(f['edc_sepa_direct_debit'].checked){
			let non_required=['edc_BIC[]'];
			let iban=f.querySelectorAll('#direct_debit_form input[name="edc_IBAN[]"]');			
			let val='';
			for(let i=0;i<iban.length;++i) val+=iban[i].value;
			let iban_test=checkiban(val);
			if(!iban_test){
				if(valid) this.scrollToEl(f['edc_IBAN[]']);
				for(let i=0;i<iban.length;++i) this.setNotValid(iban[i]);
				valid=false;
			}
			/*var els=f.querySelectorAll('#direct_debit_form input');
			for(var i=0;i<els.length;++i) if((els[i].value=='' || !els[i].checked && els[i].type=='checkbox') && non_required.indexOf(els[i].name)==-1){
				if(valid) this.scrollToEl(els[i]);
				this.setNotValid(els[i]);
				valid=false;
			}*/
		}
		if(this.is(f['cancel_old'].value) && !f['cancel_old_check'].checked){
			if(valid) this.scrollToEl(f['cancel_old_check']);
			this.setNotValid(f['cancel_old_check']);
			valid=false;			
		}
		/*if(f['change'].value=='change' && f['edc_provider'].value==''){
			this.setNotValid(f['edc_provider']);
			valid=false;				
		}*/
		this.log(f.edc_sepa_direct_debit.checked);
		this.log(f.edc_transfer.checked);
		if(!f.edc_sepa_direct_debit.checked && !f.edc_transfer.checked){
			if(valid) this.scrollToEl(f['edc_sepa_direct_debit']);
			this.setNotValid(f['edc_sepa_direct_debit']);
			this.setNotValid(f['edc_transfer']);
			valid=false			
		}
		return valid;
		/*if(!valid){
			edc.formLoading(f,true);
			return false;
		}
		subm=f.querySelectorAll('button');
		for(var i=0;i<f.length;++i) if(!f[i].classList.contains('on_submit')) f[i].disabled=true;
		for(var i=0;i<subm.length;++i){
			subm[i].style.display='inline-flex';
			subm[i].disabled=false;
		}
		var cbs=f.querySelectorAll('.edc_checkbox:not(.on_submit)');
		for(var i=0;i<cbs.length;++i) $(cbs[i]).addClass('disabled');
		el.style.display='none';
		let blocks=f.querySelectorAll('.only_on_submit');
		for(let i=0;i<blocks.length;++i) blocks[i].style.display='block';
		let req=f.querySelectorAll('*[data-set-required]');
		for(let i=0;i<req.length;++i) req[i].setAttribute('data-required','1');
		var pos=$(f).offset().top;
		$("html, body").animate({ scrollTop: pos });*/
	}
	edcp.checkOrderForm=function(el){
		if(!el) return false;
		var f=el.closest('form');
		if(!f) return false;
		valid=this.validateForm(f);
		let birth=f['edc_date_of_birth'].value;
		birth=birth.split('.');
		birth=birth.reverse();
		birth=birth.join('-');
		let age=this.calculateAge(birth);
		let sd=JSON.parse(f['steps_data'].value);
		if(birth==''){
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Geburtsdatum auswählen');
			valid=false;
		}
		if(age<18){			
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Du musst mindestens 18 Jahre alt sein');
			valid=false;
		}
		if(this.is(sd.first.lower_30) && age>30){
			if(valid) this.scrollToEl(f['edc_date_of_birth']);
			this.setNotValid(f['edc_date_of_birth'],'Du darfst nicht über 30 Jahre alt sein');
			valid=false;
		}
		if(f['edc_sepa_direct_debit'].checked){
			let non_required=['edc_BIC[]'];
			let iban=f.querySelectorAll('#direct_debit_form input[name="edc_IBAN[]"]');			
			let val='';
			for(let i=0;i<iban.length;++i) val+=iban[i].value;
			let iban_test=checkiban(val);
			if(!iban_test){
				if(valid) this.scrollToEl(f['edc_IBAN[]']);
				for(let i=0;i<iban.length;++i) this.setNotValid(iban[i]);
				valid=false;
			}
			var els=f.querySelectorAll('#direct_debit_form input');
			for(var i=0;i<els.length;++i) if((els[i].value=='' || !els[i].checked && els[i].type=='checkbox') && non_required.indexOf(els[i].name)==-1){
				if(valid) this.scrollToEl(els[i]);
				this.setNotValid(els[i]);
				valid=false;
			}
		}
		if(this.is(f['cancel_old'].value) && !f['cancel_old_check'].checked){
			if(valid) this.scrollToEl(f['cancel_old_check']);
			this.setNotValid(f['cancel_old_check']);
			valid=false;			
		}
		/*if(f['change'].value=='change' && f['edc_provider'].value==''){
			this.setNotValid(f['edc_provider']);
			valid=false;				
		}*/
		this.log(f.edc_sepa_direct_debit.checked);
		this.log(f.edc_transfer.checked);
		if(!f.edc_sepa_direct_debit.checked && !f.edc_transfer.checked){
			if(valid) this.scrollToEl(f['edc_sepa_direct_debit']);
			this.setNotValid(f['edc_sepa_direct_debit']);
			this.setNotValid(f['edc_transfer']);
			valid=false			
		}
		
		if(!valid){
			edc.formLoading(f,true);
			return false;
		}
		subm=f.querySelectorAll('button');
		for(var i=0;i<f.length;++i) if(!f[i].classList.contains('on_submit')) f[i].disabled=true;
		for(var i=0;i<subm.length;++i){
			subm[i].style.display='inline-flex';
			subm[i].disabled=false;
		}
		var cbs=f.querySelectorAll('.edc_checkbox:not(.on_submit)');
		for(var i=0;i<cbs.length;++i) $(cbs[i]).addClass('disabled');
		el.style.display='none';
		let blocks=f.querySelectorAll('.only_on_submit');
		for(let i=0;i<blocks.length;++i) blocks[i].style.display='block';
		let req=f.querySelectorAll('*[data-set-required]');
		for(let i=0;i<req.length;++i) req[i].setAttribute('data-required','1');
		var pos=$(f).offset().top;
		$("html, body").animate({ scrollTop: pos });
	}
	edcp.editOrderForm=function(el){
		if(!el) return false;
		var f=el.closest('form');
		if(!f) return false;
		subm=f.querySelectorAll('button');
		for(var i=0;i<f.length;++i) f[i].disabled=false;
		for(var i=0;i<subm.length;++i){
			if(!$(subm[i]).hasClass('check')) subm[i].style.display='none';
			else subm[i].style.display='inline-flex';
		}
		var cbs=f.querySelectorAll('.edc_checkbox');
		for(var i=0;i<cbs.length;++i) $(cbs[i]).removeClass('disabled');	
		let blocks=f.querySelectorAll('.only_on_submit');
		for(let i=0;i<blocks.length;++i) blocks[i].style.display='none';
		let req=f.querySelectorAll('*[data-set-required]');
		for(let i=0;i<req.length;++i) req[i].removeAttribute('data-required');
	}
	edcp.displayChangeFields=function(el){
		if(!el) return false;
		let cont=document.body.querySelector('#change_fields');
		if(!cont) return false;
		let f=el.closest('form');
		let els=f.querySelectorAll('.change_fields');
		let els2=f.querySelectorAll('.change_fields2');
		let t=document.body.querySelector('#date_field_title');
		document.body.querySelector('#art_des_fields').style.display='block';
		//let cont2=document.body.querySelector('#counter_fields_holder');
		//Datum der Zählerablesung" into "Datum der Wohnungsübergabe
		if(el.value=='new'){
			cont.style.display='block';
			for(let i=0;i<els.length;++i) els[i].style.display='none';
			for(let i=0;i<els2.length;++i) els2[i].style.display='block';
			f.edc_previous.dataset.required='';
			f.edc_read_date.dataset.required='1';
			//f.edc_contract.dataset.required='';
			for(let i=0;i<f.cancel_old.length;++i) f.cancel_old[i].dataset.required='';
			for(let i=0;i<f.start_supply.length;++i) f.start_supply[i].dataset.required='';
			//t.innerHTML='Datum der Wohnungsübergabe';
			document.body.querySelector('#counter_fields_holder > div:nth-child(1)').className='col_1_2';
			if(document.body.querySelector('#counter_fields_holder > div:nth-child(3)')) document.body.querySelector('#counter_fields_holder > div:nth-child(3)').className='col_1_2';
			document.body.querySelector('#counter_fields_holder > div:nth-child(2)').style.display='block';
			if(document.body.querySelector('#counter_fields_holder > div:nth-child(4)')) document.body.querySelector('#counter_fields_holder > div:nth-child(4)').style.display='block';
			if(f.edc_electriс_value2){
				f.edc_electriс_value.dataset.required='1';
				f.edc_electriс_value2.dataset.required='1';
			}
		}else{
			cont.style.display='none';
			for(let i=0;i<els.length;++i) els[i].style.display='block';
			for(let i=0;i<els2.length;++i) els2[i].style.display='none';
			f.edc_previous.dataset.required='1';
			f.edc_read_date.dataset.required='';
			//f.edc_contract.dataset.required='1';
			for(let i=0;i<f.cancel_old.length;++i) f.cancel_old[i].dataset.required='1';	
			for(let i=0;i<f.start_supply.length;++i) f.start_supply[i].dataset.required='1';
			//t.innerHTML='Datum der Zählerablesung';
			document.body.querySelector('#counter_fields_holder > div:nth-child(1)').className='';
			if(document.body.querySelector('#counter_fields_holder > div:nth-child(3)')) document.body.querySelector('#counter_fields_holder > div:nth-child(3)').className='';
			document.body.querySelector('#counter_fields_holder > div:nth-child(2)').style.display='none';
			if(document.body.querySelector('#counter_fields_holder > div:nth-child(4)')) document.body.querySelector('#counter_fields_holder > div:nth-child(4)').style.display='none';
			if(f.edc_electriс_value2){
				f.edc_electriс_value.dataset.required='';
				f.edc_electriс_value2.dataset.required='';
			}
		}
	}
	edcp.displayCancelFields=function(el){
		if(!el) return false;
		let cont=document.body.querySelector('#cancel_old');
		if(!cont) return false;
		let f=cont.closest('form');
		let cont2=f.edc_contract.closest('.fieldset');
		let cont3=f.querySelectorAll('.cancel_old');
		if(this.is(el.value)){
			cont.style.display='block';
			for(let i=0;i<cont3.length;++i) cont3[i].style.display='none';
			document.body.querySelector('#sollen_fields').style.display='block';
			//f.edc_contract.dataset.required='1';
			//cont2.querySelector('.name .red').style.display='inline';
		}else{
			cont.style.display='none';
			for(let i=0;i<cont3.length;++i) cont3[i].style.display='block';
			document.body.querySelector('#sollen_fields').style.display='none';
			//f.edc_contract.dataset.required='';
			//cont2.querySelector('.name .red').style.display='none';
		}
	}
	edcp.displaySollen=function(el){
		if(!el) return false;
		let cont=document.body.querySelector('#sollen_fields');
		if(!cont) return false;
		let f=cont.closest('form');
		let els=f.start_supply;
		if(this.is(el.value)){
			cont.style.display='none';
			for(let i=0;i<els.length;++i) els[i].dataset.required='';
		}else{
			cont.style.display='block';
			for(let i=0;i<els.length;++i) els[i].dataset.required=1;
		}
	}
	edcp.displaySupplyFields=function(el){
		if(!el) return false;
		let cont=document.body.querySelector('#desired_date');
		if(!cont) return false;
		if(el.value=='desired'){
			cont.style.display='block';
		}else{
			cont.style.display='none';			
		}
	}
	edcp.closeTabs=function(el){
		if(!el) return false;
		let cont=el.closest('.edc_tabs');
		if(!cont) return false;
		let act=cont.querySelector('.edc_tab.active');
		if(!act) return false;
		act.classList.remove('active');
		act=cont.querySelector('.navigation .active');
		if(!act) return false;
		act.classList.remove('active');		
	}
	edcp.datepickerFields=function(){
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
			$(".datepicker_no_past").datepicker({
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
				minDate: 0,
				dateFormat : 'dd.mm.yy',
			});
			$(".datepicker_no_past").attr('autocomplete','off');
			var date = new Date("2000-01-01");
			var currentMonth = date.getMonth();
			var currentDate = date.getDate();
			var currentYear = date.getFullYear();
			$(".birthdate").datepicker({
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
				//maxDate: new Date(currentYear, currentMonth, currentDate),
				defaultDate : date,
				dateFormat : 'dd.mm.yy',
			});
			//$(".birthdate").datepicker('setDate',date);
			$(".birthdate").attr('autocomplete','off');
			$(".datepicker2").datepicker({
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
				minDate: '-6w',
			});
			$(".datepicker2").attr('autocomplete','off');
			
		}catch(ex){ this.log('Datepicker error',ex); }
	}
	edcp.setTariffOutputItemsHeight=function(){
		let els=document.body.querySelectorAll('.edc_list .items > .item');
		if(!els || els.length==0) return;
		let in_row=3;
		if(w.innerWidth>1000) in_row=3;
		else if(w.innerWidth>800) in_row=2;
		else in_row=1;
		
		let heights=[];
		let heights2=[];
		let heights3=[];
		let ind=0;
		for(let i=0;i<els.length;++i){
			let el=els[i].querySelector('.tariff_characteristics .items');
			let el2=els[i].querySelector('.main_info .title');
			let el3=els[i].querySelector('.main_info .subtitle');
			if(!el){ heights[ind]=0; }
			if(!el2){ heights2[ind]=0; }
			if(!el3){ heights3[ind]=0; }
			if(in_row==1){
				if(el) el.style.minHeight='auto';
				if(el2) el2.style.minHeight='auto';
				if(el3) el3.style.minHeight='auto';
			}else{
				if(el) if(!heights[ind] || heights[ind]<el.offsetHeight) heights[ind]=el.offsetHeight;
				if(el2) if(!heights2[ind] || heights2[ind]<el2.offsetHeight) heights2[ind]=el2.offsetHeight;
				if(el3) if(!heights3[ind] || heights3[ind]<el3.offsetHeight) heights3[ind]=el3.offsetHeight;
				if(i%in_row==in_row-1) ++ind;
			}
		}
		if(in_row>1){
			ind=0;
			for(let i=0;i<els.length;++i){
				let el=els[i].querySelector('.tariff_characteristics .items');
				let el2=els[i].querySelector('.main_info .title');
				let el3=els[i].querySelector('.main_info .subtitle');
				if(el) el.style.minHeight=heights[ind].toString()+'px';
				if(el2) el2.style.minHeight=heights2[ind].toString()+'px';
				if(el3) el3.style.minHeight=heights3[ind].toString()+'px';
				if(i%in_row==in_row-1) ++ind;
				
			}
		}
	}
	edcp.edcPopupValidate=function(f){
		let res=true;
		let birth=f['birthdate'].value;
		if(birth.trim()==''){ this.setNotValid(f['birthdate']); res=false; }
		birth=birth.split('.');
		birth=birth.reverse();
		birth=birth.join('-');
		let age=this.calculateAge(birth);
		if(!age || age<18){ this.setNotValid(f['birthdate']); resfalse; }
		if(f['type'].value=='gas'){
			if(f['square'].value==''){ this.setNotValid(f['square']); res=false; }
		}else if(f['type'].value=='strom'){
			if(f['consumption'].value==''){ this.setNotValid(f['consumption']); res=false; }		
		}else{
			if(f['square'].value==''){ this.setNotValid(f['square']); res=false; }
			if(f['consumption'].value==''){ this.setNotValid(f['consumption']); res=false; }			
		}
		return res;
	}
	edcp.edcPopupProcess=function(el,data,f){
		this.log(data);
		if(!f) f=$(el).parents('form').get(0);
		f.reset();
		/*
		$(f).find('input[name="validate"]').get(0).parentNode.removeChild($(f).find('input[name="validate"]').get(0));
		this.simpleSubmit(f);*/
	}
	edcp.popupTariffChanged=function(el){
		if(!el) return false;
		let cont1=document.body.querySelector('#edc_popup_square');
		let cont2=document.body.querySelector('#edc_popup_consumption');
		if(el.value=='gas'){
			cont1.style.display='block';
			cont2.style.display='none';
		}else if(el.value=='strom'){
			cont1.style.display='none';
			cont2.style.display='block';			
		}else{
			cont1.style.display='block';
			cont2.style.display='block';
			
		}
	}
	edcp.getPostalCodesList=function(ev,el){
		let l=el.closest('form').querySelector('.edc_form_row.location');
		this.log(l);
		if(l) $(l).removeClass('active');
		if(el.value.length<3) return false;
		let it=setInterval((function(edc,el,len,l){ return function(){
			if(el.value.length!=len){
				if(l) $(l).removeClass('active');
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
								if(sel.options.length==2) sel.options[1].selected=true;
								if(l) $(l).addClass('active');
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
		}})(this,el,el.value.length,l),500);
	}
	edcp.submitTariff=function(ev,el,tid){
		ev.preventDefault();
		let f=document.forms['edc_tariff_form'];
		if(!this.isNum(tid) || $(el).hasClass('loading') || !f) return false;
		let goodies=el.closest('.item').querySelectorAll('input[name="goodies"]');
		let sel='';
		for(let i=0;i<goodies.length;++i) if(goodies[i].checked) sel=goodies[i].value;
		if($(el.closest('.single_tariff')).hasClass('heimkehrer') && !sel){
			this.scrollToEl(goodies[0].closest('.checkbox'));
			this.setNotValid(goodies[0]);
			return;
		}
		if(sel) f.goodies.value=sel;
		f.id_tariff.value=tid;
		this.submit(el,{
			'form' : f,
			'callback' : 'afterStepProcess',
		});
	}
	edcp.recalculatePrice=function(el){
		if(!el) return false;
		let par=el.closest('.single_tariff');
		if(!par || !$(par).hasClass('heimkehrer')) return false;	
		let f=document.forms['edc_tariff_form'];
		let fd=new FormData(f);
		fd.append('edc_get_heimkehrer_price',el.value);
		$.ajax({
			url: window.location,
			type: 'post',
			data: fd,
			processData: false,
			contentType: false,
			success: function (data){
				console.log(data);
				try{
					data=JSON.parse(data);
					if(data.type=='success'){
						console.log(data.success_text);
						par.querySelector('.total_price').innerHTML=data.success_text[1];
					}
				}catch(ex){ edc.log('Error',ex); }
			},
			error: function(data){},
		});	
	}
	edcp.changeCombiPricesDisplay=function(el){
		if(!el) return false;
		let t=el.closest('.single_tariff');
		if(!t) return false;
		let cont=t.querySelector('.combi_prices_holder');
		if(!cont) return false;
		jQuery(cont).toggleClass('active');
	}
	
	edcp.formDefaultActions=function(){
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
		let other=f.edc_other_debit;//querySelector('input[name="edc_other_debit"]');
		let other_form=f.querySelector('#etc_fields');
		if(other && other_form){
			for(let i=0;i<other.length;++i) other[i].addEventListener('change',(function(o){ return function(){
				let fields=['edc_etc_gender','edc_etc_firstname','edc_etc_name','edc_etc_street','edc_etc_house','edc_etc_zip','edc_etc_city'];
				if(o.is(this.value)){
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
			}})(this));
		}
	}
	edcp.fixFontSize=function(el){
		if(!el) return '';
		if(el.value.length<5){
			el.removeAttribute('style');
		}else{
			el.style.fontSize='25px';
		}
	}
	edcp.jumpingFields=function(){	
		let els=document.body.querySelectorAll('.jumping_wrapper input');
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
	w.addEventListener('load',function(){
		edc.setTariffOutputItemsHeight();
		this.addEventListener('resize',function(){
			edc.setTariffOutputItemsHeight();			
		});
	});
})(window,edc,EDC.prototype);