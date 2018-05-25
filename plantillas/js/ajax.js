function lookupCiudades(inputString) {
		stateid=$('#stateid').val();
		if((inputString.length<3) && (stateid.length == 0)) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.ajax(
			{
				type: "POST",
			    url: "ajax",
			    data: "ciudadAutoComp="+inputString+"&stateid="+stateid,
			    success: function(data){
					if(data.length >0) {
						$('#suggestions').show();
						$('#autoSuggestionsList').html(data);
					}
				}
			});
		}
} // lookup
	
function fill(thisValue) {
	$('#ciudadAutoComp').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

}
