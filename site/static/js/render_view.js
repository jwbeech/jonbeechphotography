define(function(){
	$(function(){


		var $renderBtn	= $("#renderBtn");
		$renderBtn.click(function(e){
			e.preventDefault();
			$.ajax({
				url	: "admin/doThumbRendering"
			});
			setTimeout(checkStatus, 300);
		});

		function checkStatus(){
			$.ajax({
				url		: "admin/thumbStatus",
				success	: function(json){
					updateUI(json);
				}
			});
		}

		function updateUI(json){
			$renderBtn.prop("disabled", json.busy);
			if (json.busy){
				$("#percent").html(json.percent + "% Complete");
				setTimeout(checkStatus, 300);
			}
			else{
				$("#percent").html("");
			}
		}

		checkStatus();
	});
});