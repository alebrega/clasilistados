function vaciarCampo(id,mensaje){
	if ($('#'+id).val()==mensaje){
		$('#'+id).val('');
	}
}