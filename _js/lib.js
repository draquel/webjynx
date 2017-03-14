// Form Handling
	function setForm(){
		"use strict";
		if(arguments.length > 0){
			var datastr = arguments[0];
			if(arguments.length > 1){ datastr += "&i="+arguments[1]; } 
			$.ajax({url:'/ajax.php',method:'POST',async:true,dataType:"json",data:datastr,
				complete: function(xhr){
					var data = JSON.parse(xhr.responseText);
					$(".modal-title").html(data[0]);
					$(".modal-body").html(data[1]);
					$(".modal-dialog").addClass("modal-lg");
					$(".trumbowyg").trumbowyg({
						btns: [	['viewHTML'],['formatting'],'btnGrp-semantic',['superscript','subscript'],['link'],['insertImage'],'btnGrp-justify','btnGrp-lists',['horizontalRule'],['removeformat'] ],
						autogrow: true
					});
					$(".modal-body").find("form").submitForm();
					$('#Modal').modal('show');
				}
			});
			return true;
		}else{ return false; }
	}
	//Implement FormData() for submission to support file uploads 
	jQuery.fn.submitForm = function(){
		"use strict";
		$(this).submit(function(e){
			e.preventDefault();
			var formData = new FormData();
			$(this).find("input, textarea, select").each(function(){
				if($(this).is('input[type=checkbox]')){ if($(this).is(':checked')){ formData.append($(this).attr('id'),1); }else{ formData.append($(this).attr('id'),0); }	}
				else if($(this).is('input[type=file]')){ formData.append($(this).attr('id'),$(this)[0].files[0]); }
				else if($(this).val() === null || $(this).val() === "" || typeof $(this).val() === 'undefined'){ formData.append($(this).attr('id'),"NULL"); }
				else if(Array.isArray($(this).val())){ formData.append($(this).attr('id'),$(this).val().toString()); }
				else{ formData.append($(this).attr('id'),$(this).val()); }
			});
			$.ajax({url:'/ajax.php',method:'POST',async:true,data:formData,
				processData: false,
      			contentType: false,
				complete: function(xhr){
					var data = JSON.parse(xhr.responseText);
					if(data[0]){
						$(".modal-body > .alert").html("<strong>Congratulations!</strong> "+data[1]).addClass("alert-success").removeClass("hidden");
						$(".modal-body button[type=submit]").addClass("hidden");
						setTimeout(function(){ $('#Modal').modal('hide'); location.reload(); },2000);
					}else{ $(".modal-body > .alert").addClass("alert-danger").removeClass("hidden").html("<strong>Opps!</strong> "+data[1]); }
				}
			});
		});
	};
//Google Analytics
	//Initialize Tracker
	function gaTracker(id){
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};
		ga.l=+new Date;
		if(ga){
			ga('create', id, 'auto');
			return true;
		}else{ return false; }
	}
	//Track page view
	function gaTrack(path, title) {
		if(ga){
			ga('set', { page: path, title: title });
			ga('send', 'pageview');
			return true;
		}else{ return false; }
	}
	function gaEvent(category,action,label,val){
		if(ga){
			ga('send','event',category,action,label,val);
			return true;
		}else{ return false; }
	}
//MISC
	function validateEmail(email) {
		"use strict";
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
		return re.test(email);
	}
	function validatePhone(phone){
		"use strict";
		var rp = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		return rp.test(phone);
	}
	function preload(images,Callback) {
		"use strict";
		if(images && images !== null){
			var imagesLength = images.length;
			var loadedCounter = 0;
			for (var i = 0; i < imagesLength; i++) {
				var cacheImage = new Image();
				cacheImage.onload = function(){
					loadedCounter++;
					if (loadedCounter === imagesLength-1) {
						if ($.isFunction(Callback)){ Callback(); }
					}
				};
				cacheImage.src = images[i];
			}
		}else{ if($.isFunction(Callback)){ Callback(); } }
	}