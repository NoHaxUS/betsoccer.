moment().format(); 
$(function () {
	$('#datetimepicker1').datetimepicker({
		format: 'YYYY-MM-DD H:mm:ss',
        minDate: moment().toDate()
	}

	);
});
