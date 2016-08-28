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