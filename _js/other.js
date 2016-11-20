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
// Permalink Handling Functions
	function browserNav(){
		$(window).bind('hashchange',function(){
			if(window.pgTrans !== true){
				var linkVars = capturePermalink();
				if(linkVars.pg === "" || typeof linkVars.pg === "undefined"){ goToPage("#content","page/index.php",null); }
				else{ 
					var page = "";
					if(linkVars.pg === 'index'){ linkVars.pg = ""; page = "page/index.php"; }else{ if(linkVars.pg.charAt(linkVars.pg.length-1) === "/"){ page = "page/"+linkVars.pg;}else{ page = "page/"+linkVars.pg+".php";} }
					goToPage("#content",page,null);
				}
			}
		});
	}
	function catchPermalink(func){
		var linkVars = capturePermalink();
		if(linkVars){
			if(typeof linkVars.pg !== 'undefined' && linkVars.pg !== ""){
				var page = "";
				if(linkVars.pg === 'index'){ $("#menu").hide(); linkVars.pg = ""; page = "page/index.php"; }else{ $("#menu").show(); if(linkVars.pg.charAt(linkVars.pg.length-1) === "/"){ page = "page/"+linkVars.pg;}else{ page = "page/"+linkVars.pg+".php";} }
				goToPage("#content",page,func);
			}
		}else{ goToPage("#content","page/index.php",func); }
	}
	function capturePermalink(){
		var urlParts = window.location.href.split("#");
		if(typeof urlParts[1] !== 'undefined' && urlParts[1] !== ""){
			var linkParts = urlParts[1].split("&");
			var linkVars = new Array();
			for(var i = 0;i < linkParts.length;i++){
				var index = linkParts[i].indexOf("=");
				linkVars[linkParts[i].substr(0, index)] = linkParts[i].substr(index + 1);
			}
			return linkVars;
		}else{return false;}
	}
	function createPermalink(vars){
		var result = "";
		for(var i in vars){
			if(vars[i] !== ""){
				if (result !== ""){result += "&";}
				result += i + "=" + vars[i];
			}
		}
		if(result === ""){return false;}else{return "#"+result;}
	}
	function updatePermalinkVar(varID,val){
		var linkVars = capturePermalink();
		if(linkVars){linkVars[varID] = val;}else{linkVars = new Array();linkVars[varID] = val;}
		var href = window.location.href;
		var idx = href.indexOf("#");
		var prefix = "";
		if(idx !== -1){prefix = href.substr(0,idx);}else{prefix = href;}
		var nls = createPermalink(linkVars);
		if(nls){window.location.href = prefix+createPermalink(linkVars);}else{window.location.href = prefix+"#";}
	}
	function remPermalink(){
		var urlParts = window.location.href.split("#");
		window.location.href = urlParts[0];
	}