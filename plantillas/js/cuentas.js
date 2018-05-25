function seleccFecha(selectElement){
	form=selectElement.form;
	selectedValue=selectElement.options[selectElement.selectedIndex].value;
	now=new Date();
	today=new Date(now.getFullYear(),now.getMonth(),now.getDate());
	var startDate,endDate;
	if(selectedValue=="clear"){
		form.elements['anioDesde'].value="";
		form.elements['mesDesde'].value="";
		form.elements['diaDesde'].value="";
		form.elements['anioHasta'].value="";
		form.elements['mesHasta'].value="";
		form.elements['diaHasta'].value="";
	}else{
		if(selectedValue=="ultimos3meses"){
			startDate=new Date(today.getFullYear(),today.getMonth()-3,today.getDate());
			endDate=today;
		}else if(selectedValue=="3-6meses"){
			startDate=new Date(today.getFullYear(),today.getMonth()-6,today.getDate());
			endDate=new Date(today.getFullYear(),today.getMonth()-3,today.getDate());
		}else if(selectedValue=="6-9meses"){
			startDate=new Date(today.getFullYear(),today.getMonth()-9,today.getDate());
			endDate=new Date(today.getFullYear(),today.getMonth()-6,today.getDate());
		}else if(selectedValue=="9-12meses"){
			startDate=new Date(today.getFullYear()-1,today.getMonth(),today.getDate());
			endDate=new Date(today.getFullYear(),today.getMonth()-9,today.getDate());
		}
		/*else if(selectedValue=="last30days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-30);endDate=today;
		}else if(selectedValue=="30-60days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-60);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-30);
		}else if(selectedValue=="60-90days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-90);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-60);
		}else if(selectedValue=="90-120days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-120);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-90);
		}else if(selectedValue=="7-14days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-14);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-7);
		}else if(selectedValue=="14-21days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-21);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-14);
		}else if(selectedValue=="21-28days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-28);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-21);
		}else if(selectedValue=="28-35days"){
			startDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-35);
			endDate=new Date(today.getFullYear(),today.getMonth(),today.getDate()-28);
		}*/
		form.elements['anioDesde'].value=startDate.getFullYear();
		form.elements['mesDesde'].value=startDate.getMonth()+1;
		form.elements['diaDesde'].value=startDate.getDate();
		form.elements['anioHasta'].value=endDate.getFullYear();
		form.elements['mesHasta'].value=endDate.getMonth()+1;
		form.elements['diaHasta'].value=endDate.getDate();
	}
}
