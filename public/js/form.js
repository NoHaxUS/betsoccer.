var teste;
var allRadios = [];
var booRadio;
//Deixa nome dos time em letra maiscula
$(document).ready(function() {
    $("select.special-flexselect").flexselect({ hideDropdownOnEmptyInput: true });
});
$(function() {
    $('#upper').keyup(function() {
        this.value = this.value.toLocaleUpperCase()
    });
});
$(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
            $(this).toggleClass('open');
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
            $(this).toggleClass('open');
        });
});


//check e uncheck do iputs radio da tela de apostar
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
//mudando o layout do checkbox da tela de login
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