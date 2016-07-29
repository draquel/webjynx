//Browser Navigational Overide Functions
	jQuery.fn.setContent=function(scrollto){
		var target=$(this).attr("target");
		var pg=$(this).attr("href");
		if(target==="#"||target===""||target===null||typeof target==="undefined"){return 0;}
		else{
			window.pgTrans=true;
			$("#loader-page").fadeIn(50);
			$("#menu li").removeClass("active");
			$(this).parent().addClass("active");
			$(target).fadeOut(250,function(){
				if(pg=="/pg/index"){$("#menu").hide();}else{$("#menu").show();}
				$.ajax({url:'/ajax.php',method:'POST',async:true,dataType:"json",data:"ari=4&p="+pg,
					complete: function(xhr){
						var data = JSON.parse(xhr.responseText);
						$(target).html(data[0]);
						window.history.pushState({"html":data[0],"url":data[1]['path-file']},data[1]['meta-title'],data[1]['path-ui']);
						document.title = (document.title.split("-"))[0] +"- "+data[1]['meta-title'];
						$("meta[name='description']").attr("content",data[1]['meta-description']);
						gaTrack(data[1]['path-ui'],data[1]['meta-title']);
						SCCallback(target,scrollto);
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
	 	 $("#menu li").removeClass("active").find("[href='"+page.replace(".php","").replace("/page/","/pg/")+"']").parent().addClass("active");
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
//jQuery labeless input field functionality
	jQuery.fn.inputDefault = function(){ $(this).focus(function(){ if($(this).val() == $(this).attr('title')){ $(this).val(""); } }).blur(function(){ if($(this).val() == ""){ $(this).val($(this).attr('title')); } }); }
//Wordpress iframe Integration
	function loadBlog(src){	$("<iframe />").attr("style","width:100%;border:0;overflow:hidden;").attr("id","blog").attr('src',src).appendTo("#bcont"); }
//FORM SUBMISSIONS
	jQuery.fn.submitNLS = function(){
		$(this).submit(function(e){
			e.preventDefault();
			$("#status").hide();
			var name = $(this).find("[name=name]").val();
			var company = $(this).find("[name=company]").val();
			var email = $(this).find("[name=email]").val();
			var reason = $(this).find("[name=reason]").val();
			var error = false;
			
			if(name == "Full Name" || name == "" || name == null || typeof name == "undefined" || name.indexOf(" ") == -1){ $(this).find("[name=name]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=name]").css("border","0").css("margin",""); }
			if(company == "Company" || company == "" || company == null || typeof company == "undefined"){ $(this).find("[name=company]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=company]").css("border","0").css("margin",""); }
			if(email == "Email" || email == "" || email == null || typeof email == "undefined" || !validateEmail(email)){ $(this).find("[name=email]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=email]").css("border","0").css("margin",""); }
			if(reason == "Reason for Following" || reason == "" || reason == null || typeof reason == "undefined"){ $(this).find("[name=reason]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=reason]").css("border","0").css("margin",""); }
			if(!error){
				$.ajax({
					url: 'ajax.php',method:'POST',async:true,data:"ari=1&n="+name+"&c="+company+"&e="+email+"&r="+reason,
					complete: function(){
						$("#status").fadeIn();
						$("input[type=text]").each(function(){ $(this).val($(this).attr('alt')); });
					}
				});
			}
		});
	}
	jQuery.fn.submitWWU = function(){
		$(this).submit(function(e){
			e.preventDefault();
			$("#status").hide();
			var name = $(this).find("[name=name]").val();
			var company = $(this).find("[name=company]").val();
			var email = $(this).find("[name=email]").val();
			var message = $(this).find("[name=message]").val();
			var error = false;
			
			if(name == "Full Name" || name == "" || name == null || typeof name == "undefined" || name.indexOf(" ") == -1){ $(this).find("[name=name]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=name]").css("border","0").css("margin",""); }
			if(company == "Company" || company == "" || company == null || typeof company == "undefined"){ $(this).find("[name=company]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=company]").css("border","0").css("margin",""); }
			if(email == "Email" || email == "" || email == null || typeof email == "undefined" || !validateEmail(email)){ $(this).find("[name=email]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=email]").css("border","0").css("margin",""); }
			if(message == "Message" || message == "" || message == null || typeof message == "undefined"){ $(this).find("[name=message]").css("border","3px solid red").css("margin","3px auto"); error = true; }else{ $(this).find("[name=message]").css("border","0").css("margin",""); }
			if(!error){
				$.ajax({
					url: 'ajax.php',method:'POST',async:true,data:"ari=2&n="+name+"&c="+company+"&e="+email+"&m="+message,
					complete: function(){
						$("#status").fadeIn();
						$("input[type=text]").each(function(){ $(this).val($(this).attr('alt')); });
					}
				});
			}
		});
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
	}
	//Google Analytics - Initialize Tracker
	function gaTracker(id){
		//$.getScript('//www.google-analytics.com/analytics.js');
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		ga('create', id, 'auto');
	};
	//Google Analytics - Track page view
	function gaTrack(path, title) {
		ga('set', { page: path, title: title });
		ga('send', 'pageview');
	}