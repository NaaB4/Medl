(function(w,edc,edcp){	
	edcp.addGoodie=function(){
		$('#goodie_popup').addClass('active');
		$('#goodie_popup .add_title').addClass('active');
		$('#goodie_popup .edit_title').removeClass('active');
		$('#goodie_popup form').get(0).reset();
		$('#goodie_popup form input[name="edc_goodie"]').val('new');
	}
	edcp.editGoodie=function(id){
		$('#goodie_popup').addClass('active');
		$('#goodie_popup .edit_title').addClass('active');
		$('#goodie_popup .add_title').removeClass('active');
		$('#goodie_popup form').get(0).reset();
		$('#goodie_popup form input[name="name"]').val(w.edc_goodies[id].name);
		$('#goodie_popup form textarea[name="description"]').val(w.edc_goodies[id].description);
		$('#goodie_popup form input[name="price"]').val(w.edc_goodies[id].price)
		document.body.querySelector('#goodie_popup form input[name="young"]').checked=w.edc_goodies[id].young==1 ? true : false;
		$('#goodie_popup form input[name="edc_goodie"]').val(id);
	}
	edcp.deleteGoodie=function(pid,c){
		if(confirm(c)){
			this.windowLoading();
			var obj=this;
			$.ajax({
				url: window.location,
				type: 'post',
				data: 'edc_remove_goodie='+pid.toString(),
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
			});
		}	
	}
	edcp.removeSelectedGoodies=function(){
		if(!document.body.querySelector('#goodies_table')){ alert(this.LANG('error','Error')); return false; }
		
		if(confirm(this.LANG('undone_action','Are you sure? This action can not be undone.'))){
			var checks=document.body.querySelectorAll('#goodies_table tbody tr .check-column input[type="checkbox"]');
			var ids=[];
			for(var i=0;i<checks.length;++i){
				if(checks[i].checked){
					ids.push(checks[i].name.replace('goodie_',''));
				}
			}
			if(ids.length==0){ alert(this.LANG('no_checkbox_checked','No items has been selected')); }	
			this.windowLoading();
			var obj=this;
			$.ajax({
				url: window.location,
				type: 'post',
				data: 'edc_remove_goodies='+ids.join(','),
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
	edcp.shortcodeFromSelected=function(){
		if(!document.body.querySelector('#tariffs_table')){ alert(this.LANG('error','Error')); return false; }
		var checks=document.body.querySelectorAll('#tariffs_table tbody tr .check-column input[type="checkbox"]');
		var ids=[];
		for(var i=0;i<checks.length;++i){
			if(checks[i].checked){
				ids.push(checks[i].name.replace('tariff_',''));
			}
		}
		if(ids.length==0){ alert(this.LANG('no_checkbox_checked','No items has been selected')); }
		document.body.querySelector('#edc_shortcode_popup input[name="tariff_ids"]').value=ids.join(',');
		$('#edc_shortcode_popup').addClass('active');		
	}
	edcp.redrawShortcode=function(el){
		if(!el) return false;
		let f=el.closest('form');
		if(!f) return false;
		let cont=f.querySelectorAll('.shortcode_result');
		if(!cont || !cont.length) return false;
		if(f.tariff_type.value=='combi'){
			this.getCombiShortcode(f,cont);
		}else{
			this.getOtherShortcode(f,cont);			
		}
	}
	edcp.getCombiShortcode=function(f,cont){
		let map={
			'st_example_annual_consumption' : 'st_example_annual_consumption',
			'st_per_kwh' : 'st_per_kwh',
			'st_per_month' : 'st_per_month',
			'g_example_annual_consumption' : 'g_example_annual_consumption',
			'g_per_kwh' : 'g_per_kwh',
			'g_per_month' : 'g_per_month',
			'total_price' : 'total_price',
			'tariff_ids' : 'tariff_ids',
			'type' : 'type',
			'agb_link' : 'agb_link',
			'price_link' : 'price_link',
			'young' : 'young',
		};
		f.from_annual_consumption.value=f.from_annual_consumption.value.replace(',','.');
		f.to_annual_consumption.value=f.to_annual_consumption.value.replace(',','.');
		f.example_annual_consumption.value=f.example_annual_consumption.value.replace(',','.');
		f.per_kwh.value=f.per_kwh.value.replace(',','.');
		f.per_month.value=f.per_month.value.replace(',','.');
		f.total_price.value=f.total_price.value.replace(',','.');
		let sc=f.only_link.checked ? 'EDCPopupLink' : 'EDCBadge';
		for(let i=0;i<cont.length;++i)
			cont[i].innerHTML='['+sc+' \
			'+map.st_example_annual_consumption+'="'+f.st_example_annual_consumption.value.toString()+'"\
			'+map.st_per_kwh+'="'+f.st_per_kwh.value.toString()+'"\
			'+map.st_per_month+'="'+f.st_per_month.value.toString()+'"\
			'+map.g_example_annual_consumption+'="'+f.g_example_annual_consumption.value.toString()+'"\
			'+map.g_per_kwh+'="'+f.g_per_kwh.value.toString()+'"\
			'+map.g_per_month+'="'+f.g_per_month.value.toString()+'"\
			'+map.total_price+'="'+f.total_price.value.toString()+'"\
			'+map.tariff_ids+'="'+f.tariff_ids.value.toString()+'"\
			'+map.type+'="'+f.tariff_type.value.toString()+'"\
			'+map.agb_link+'="'+f.agb_link.value.toString()+'"\
			'+map.price_link+'="'+f.price_link.value.toString()+'"\
			'+map.young+'="'+(f.young.checked ? '1' : '0')+'"\
		]';		
	}
	edcp.getOtherShortcode=function(f,cont){
		let map={
			'from_annual_consumption' : 'from_annual_consumption',
			'to_annual_consumption' : 'to_annual_consumption',
			'example_annual_consumption' : 'example_annual_consumption',
			'per_kwh' : 'per_kwh',
			'per_month' : 'per_month',
			'total_price' : 'total_price',
			'tariff_ids' : 'tariff_ids',
			'type' : 'type',
			'agb_link' : 'agb_link',
			'price_link' : 'price_link',
			'young' : 'young',
		};
		f.from_annual_consumption.value=f.from_annual_consumption.value.replace(',','.');
		f.to_annual_consumption.value=f.to_annual_consumption.value.replace(',','.');
		f.example_annual_consumption.value=f.example_annual_consumption.value.replace(',','.');
		f.per_kwh.value=f.per_kwh.value.replace(',','.');
		f.per_month.value=f.per_month.value.replace(',','.');
		f.total_price.value=f.total_price.value.replace(',','.');
		let sc=f.only_link.checked ? 'EDCPopupLink' : 'EDCBadge';
		for(let i=0;i<cont.length;++i)
			cont[i].innerHTML='['+sc+' \
			'+map.from_annual_consumption+'="'+f.from_annual_consumption.value.toString()+'"\
			'+map.to_annual_consumption+'="'+f.to_annual_consumption.value.toString()+'"\
			'+map.example_annual_consumption+'="'+f.example_annual_consumption.value.toString()+'"\
			'+map.per_kwh+'="'+f.per_kwh.value.toString()+'"\
			'+map.per_month+'="'+f.per_month.value.toString()+'"\
			'+map.total_price+'="'+f.total_price.value.toString()+'"\
			'+map.tariff_ids+'="'+f.tariff_ids.value.toString()+'"\
			'+map.type+'="'+f.tariff_type.value.toString()+'"\
			'+map.agb_link+'="'+f.agb_link.value.toString()+'"\
			'+map.price_link+'="'+f.price_link.value.toString()+'"\
			'+map.young+'="'+(f.young.checked ? '1' : '0')+'"\
		]';	
	}
	edcp.showShortcodeType=function(el){
		document.body.querySelector('#type_combi').style.display='none';
		document.body.querySelector('#type_other').style.display='none';
		if(el.value=='combi'){
			document.body.querySelector('#type_combi').style.display='block';			
		}else{
			document.body.querySelector('#type_other').style.display='block';			
		}
	}
})(window,edc_admin,EDC.prototype);