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
	}
});
$(function(){
    $('.button-checkbox').each(function(){
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
            };

        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });

        $checkbox.on('change', function () {
            updateDisplay();
        });

        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');
            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            } 
            else 
            { 
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }
        function init() {
            updateDisplay();
            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
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