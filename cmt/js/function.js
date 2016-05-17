;(function($, window, document, undefined) {
	
	var $win = $(window);
	var $doc = $(document);
	
	$doc.ready(function() {
		
		if ($('#update_parent').val()) window.opener.location.reload();
		
		$('input[type=file]').change(function() { 
			$("button[name='cmt_save']").click();
		});
		
		if ($(".popup")){
			$(window).keydown(function(event) {
				if (!(event.which == 83 && event.ctrlKey)) return true;
				$("button[name='cmt_save']").click();
				event.preventDefault();
				return false;
			});
		}
		
		$(".add").click(function(event){
			event.preventDefault();
			popup('add_'+$(this).attr('data-content'), 'index.php?modul='+$(this).attr('data-content')+'&popup=1&action=add');
		});
		
		$(".edit").click(function(event){
			event.preventDefault();
			popup($(this).attr('id'), 'index.php?modul='+$(this).attr('data-content')+'&id='+$(this).attr('id')+'&popup=1&action=edit');
		});
		
		$(".copy").click(function(event){
			event.preventDefault();
			popup($(this).attr('id'), 'index.php?modul='+$(this).attr('data-content')+'&id='+$(this).attr('id')+'&popup=1&action=copy');
		});
		
		$(".delete").click(function(event){
			event.preventDefault();
			confirm_delete = confirm(unescape('Wollen Sie den Eintrag wirklich l%F6schen?'));
			if (confirm_delete == true) {
				$.post("index.php", { delete: $(this).attr('id'), modul: $(this).attr('data-content')}, function(data){
					location.reload();
				});
			}
		});
		
		$(".icon-cell").click(function(event){
			event.preventDefault();
			var $val = $(this).attr('class');
			$val = $val.replace('icon-cell', '');
			$val = $val.replace(' ', '');
			if ($val.match(/activate/i)){
				location = 'index.php?modul='+$(this).attr('data-content')+'&activate='+$(this).attr('id')+'&action='+$val.replace('_activate','');
			}
			if ($val.match(/deactivate/i)){
				location = 'index.php?modul='+$(this).attr('data-content')+'&deactivate='+$(this).attr('id')+'&action='+$val.replace('_deactivate','');
			}
		});
		
		$(".activate").click(function(event){
			event.preventDefault();
			location = 'index.php?modul='+$(this).attr('data-content')+'&activate='+$(this).attr('id');
		});
		
		$(".deactivate").click(function(event){
			event.preventDefault();
			location = 'index.php?modul='+$(this).attr('data-content')+'&deactivate='+$(this).attr('id');
		});
		
		function popup(name,url){
			this.name='edit_'+name;
			tmp=window.open(url, 'form_'+name, 'width=1247,height=800,status=no,scrollbars=yes,resizable=no');
			tmp.focus();
		}
		
		$(".toggle-sidebar").on("click", function(event){
			event.preventDefault();
			if ($("nav").hasClass("show")) {
				$("main").animate({ marginLeft: "40px" }, "fast");
				$("nav").animate({ marginLeft: "-300px" }, "fast", function() { 
					$.post("lib/js_functions.php", { toggle_sidebar: "hidden" });
					$(this).addClass("hidden").removeClass("show");
				});
			} else {
				$("main").animate({ marginLeft: "340px" }, "fast");
				$("nav").animate({ marginLeft: "0" }, "fast", function() { 
					$.post("lib/js_functions.php", { toggle_sidebar: "show" });
					$(this).addClass("show").removeClass("hidden");
				});
			}
		});
		
		$(".filter-title .icon-toggle").on("click", function(event){
			var $icon = $(this);
			var $val = $(this).attr('id');
			if ($(this).parent().parent().next("."+$val).hasClass("show")) {
				$("."+$val).slideUp( "fast", function() {
					$("#target :input").prop("disabled", true);
					$.post("lib/js_functions.php", { toggle_val : "hidden", toggle_type: $val });
					$($icon).addClass("inactive").removeClass("active");
					$(this).addClass("hidden").removeClass("show");
					$(this).find(":input").prop("disabled", true);
				});
			} else {
				$("."+$val).slideDown( "fast", function() {
					$.post("lib/js_functions.php", { toggle_val : "show", toggle_type: $val });
					$($icon).addClass("active").removeClass("inactive");
					$(this).addClass("show").removeClass("hidden");
					$(this).find(":input").prop("disabled", false);
				});
			}
		});
		
		$(".filter-head .icon-toggle").on("click", function(event){
			var $icon = $(this);
			if ($(this).parent().parent().next(".filter-content").hasClass("show")) {
				$(".filter-content").slideUp( "slow", function() {
					$($icon).addClass("inactive").removeClass("active");
					$.post("lib/js_functions.php", { toggle_filter : "hidden" });
					$(this).addClass("hidden").removeClass("show");
				});
			} else {
				$(".filter-content").slideDown( "slow", function() {
					$.post("lib/js_functions.php", { toggle_filter : "show" });
					$($icon).addClass("active").removeClass("inactive");
					$(this).addClass("show").removeClass("hidden");
				});
			}
		});
		
		$(".popup-title .icon-toggle").on("click", function(event){
			var $icon = $(this);
			var $val = $(this).attr('id');
			if ($(this).parent().parent().next("."+$val).hasClass("show")) {
				$("."+$val).slideUp( "fast", function() {
					$("#target :input").prop("disabled", true);
					$.post("lib/js_functions.php", { toggle_val : "hidden", toggle_type: $val });
					$($icon).addClass("inactive").removeClass("active");
					$(this).addClass("hidden").removeClass("show");
					$(this).find(":input").prop("disabled", true);
				});
			} else {
				$("."+$val).slideDown( "fast", function() {
					$.post("lib/js_functions.php", { toggle_val : "show", toggle_type: $val });
					$($icon).addClass("active").removeClass("inactive");
					$(this).addClass("show").removeClass("hidden");
					$(this).find(":input").prop("disabled", false);
				});
			}
		});
		
		$(".reload_select").change(function(event){
			var $regex = /(.*)_level_(.*)/i;
			var $level = $regex.exec($(this).attr('name'));
			if ($level) {
				$level = $regex.exec($(this).attr('name'))[2];
				$(".reload_select").each(function(){
					if ($regex.exec($(this).attr('name'))){
						if ($regex.exec($(this).attr('name'))[2] > $level)$(this).val(0);
					}
				});
			}
			if ($("button[name='cmt_save']")){
	  		$("button[name='cmt_save']").val('reload');
	  		$("button[name='cmt_save']").click();
	  	}
			if ($("button[name='cmt_filter']")){
	  		$("button[name='cmt_filter']").val('reload');
	  		$("button[name='cmt_filter']").click();
	  	}
		});
		
		$('.icon-inputadd').click(function(){
			var $val = $(this).attr('id');
			$.get("lib/js_functions.php", { form_inputadd: $val+1 }).done(function(data) {
				$('#'+$val).parent().parent().append(data);
			});
		});
		
//		$('.icon-date').click(function(){
//			var visible = $('#ui-datepicker-div').is(':visible');
//       $('.datepicker').datepicker(visible ? 'hide' : 'show');
//		});
//		
//		$(".datepicker").datepicker({
//      changeMonth: true,
//      changeYear: true,
//      dateFormat: 'dd.mm.yy',
//      showOn: false
//    });
    
    var fixWidth = function(e, ui) {  
    	ui.children().each(function() {  
    		$(this).width($(this).width());
    	});
    	return ui;
    };
    
    $(".sortable").sortable({
      connectWith: $(".sortable"),
      helper: fixWidth,
      axis: 'y',
      update: function (event, ui) {
        var data = $(this).sortable('toArray');
				$.post("lib/js_functions.php?update_sort_order=true", {sort: data}, function(data){
					location.reload();
				});
    }
    });
    
    $(".sortable").disableSelection();
				
	});

})(jQuery, window, document);
