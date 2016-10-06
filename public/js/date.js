moment().format(); 
$(function () {
	$('#datetimepicker1').datetimepicker({
		format: 'YYYY-MM-DD H:mm:ss',
		minDate: moment().startOf('day').hour(23).minute(59)
	}

	);
});
