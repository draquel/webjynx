//Browser Navigational Overide Functions
	jQuery.fn.setContent=function(ari,scrollto){
		var target=$(this).attr("target");
		var pg=$(this).attr("href");
		if(target==="#"||target===""||target===null||typeof target==="undefined"){return 0;}
		else{
			window.pgTrans=true;
			$("#loader-page").fadeIn(50);
			$("#menu li").removeClass("active");
			$(this).parent().addClass("active");
			$(target).fadeOut(250,function(){
				if(pg=="/"){$("#menu").hide();}else{$("#menu").show();}
				$.ajax({url:'/ajax.php',method:'POST',async:true,dataType:"json",data:"ari="+ari+"&pp="+pg,
					complete: function(xhr){
						var data = JSON.parse(xhr.responseText);
						$(target).html(data[0]);
						window.history.pushState({"html":data[0],"url":data[2]['path-file']},data[2]['meta-title'],data[2]['path-ui']);
						document.title = (document.title.split("-"))[0] +"- "+data[2]['meta-title'];
						$("meta[name='description']").attr("content",data[2]['meta-description']);
						$("meta[name='keywords']").attr("content",data[2]['meta-keywords']);
						gaTrack(data[2]['path-ui'],data[2]['meta-title']);
						preload(data[1],SCCallback(target,scrollto));
					}
				});
			});
		}
	};
	function SCCallback(target,scrollto){
		setTimeout(function(){$(target).fadeIn(250,null);},100);
		$("#loader-page").fadeOut(50);resetSessTimeout();
		if(typeof scrollto!=='undefined'&&scrollto!==null){$('html, body').animate({scrollTop:($(scrollto).offset().top-5)},750);}
		window.pgTrans=false;
	}
	function goToPage(target,page,func){
	 	 $("#menu li").removeClass("active").find("[href='"+page.replace(".php","").replace("/page/","/")+"']").parent().addClass("active");
		 if(page == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
		 $(target).fadeOut(250,function(){ $(this).load(page,function(responseTxt){ window.history.pushState({"html":responseTxt,"url":page},""); $(this).fadeIn(250,func); }); });
	}
//Session Handling Functions
	function setSessTimeout(){
		window.sess_left_sec = 20*60;
		sessTime_left();
	}
	function resetSessTimeout(){ window.sess_left_sec = 20*60; }
	function sessTime_left(){
		window.sess_left_sec--;
		var sec = 0; var left_min = Math.floor(window.sess_left_sec / 60);
		if(left_min === 0){ sec = window.sess_left_sec; }
		else{ sec = window.sess_left_sec - (left_min * 60); }
		if(sec < 10){ sec = "0"+sec; }
		var page_output = left_min + ":" +sec;
		//document.getElementById("sessTimeout").innerHTML = page_output;
		if(window.sess_left_sec > 0){ setTimeout(function(){ sessTime_left(); }, 1000);}
		else{ location.reload(); }
	}
//Google Analytics
	//Initialize Tracker
	function gaTracker(id){
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		if(ga){
			ga('create', id, 'auto');
			return true;
		}else{ return false; }
	};
	//Track page view
	function gaTrack(path, title) {
		if(ga){
			ga('set', { page: path, title: title });
			ga('send', 'pageview');
			return true;
		}else{ return false; }
	}
//MISC
	function validateEmail(email) {
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		return re.test(email);
	}
	function validatePhone(phone){
		var rp = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		return rp.test(phone);
	}
	function preload(images,Callback) {
		if(images && images != null){
			var imagesLength = images.length;
			var loadedCounter = 0;
			for (var i = 0; i < imagesLength; i++) {
				var cacheImage = new Image();
				cacheImage.onload = function(){
					loadedCounter++;
					if (loadedCounter == imagesLength-1) {
						if ($.isFunction(Callback)){ Callback(); }
					}
				};
				cacheImage.src = images[i];
			}
		}else{ if($.isFunction(Callback)){ Callback(); } }
	}
	jQuery.fn.inputDefault = function(){ $(this).focus(function(){ if($(this).val() == $(this).attr('title')){ $(this).val(""); } }).blur(function(){ if($(this).val() == ""){ $(this).val($(this).attr('title')); } }); }