var teste;
var allRadios = [];
var booRadio;
$('input:radio').click(function() { 
	if(allRadios[teste = $(this).attr('name')]!=null){
		this.checked = false;
		booRadio = null;
		allRadios[teste = $(this).attr('name')] = booRadio;
	}else{		
		booRadio = this;
		allRadios[$(this).attr('name')] = booRadio;
		console.log(allRadios);
	}
	$.each(allRadios[$(this).attr('name')], function(index, val) {
	  	alert(index);
		alert(val);
	});	
});
/*
$('input:radio').click(function() { 
InitRadio($(this).attr('name'));
});

function InitRadio(name){
 val=0;
    //$.each($(':radio[name="'+name+'"]'), function(){
    console.log(1);
    $(this).val(val++);
    $(this).attr('chk', '0');
    $(this).on("click", function(event){
    SetRadioButtonChkProperty($(this).val(), name);    
    //});    
});
}

function SetRadioButtonChkProperty(val, name){
    $.each($(':radio[name="'+name+'"]'), function(){
        if ($(this).val()!=val)
            $(this).attr('chk', '0');
        else {if ($(this).attr('chk') == '0')
            $(this).attr('chk', '1');
        else {
            $(this).attr('chk', '0');
           $(this).prop('checked', false); 
        }}            
    });
}

*/


/*
$(document).ready(function(){
	$('input:radio[name=palpite1]').click(function() {
		
		var ans1 = $('input[name=teste]:radio:checked').val();
		console.log(teste);
	});
});
*/