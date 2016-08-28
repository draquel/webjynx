<?php
		
?>
<!-- Page Specific Styles -->
	<style>	#pg > div:nth-child(1){ background-image:url('/img/stock_head1.svg'); } #signin{ margin-top: 50px; } #signin .btn{ padding:6px 18px; }</style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!-- Page Content -->
    <div id="pg" class="container-fluid">
        <div class="row blue_bg">
            <div></div>
        </div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                
                <div id="signin" class="col-sm-4 col-sm-offset-4">
                    <div class="alert hidden" role="alert">
                      <span class="glyphicon" aria-hidden="true"></span>
                      <span class="sr-only">Message: </span>
                      <span class="alert-msg"></span>
                    </div>
                    <div class="col-sm-8 col-sm-offset-2">
                        <form id="loginForm">
                            <input name="user" type="text" class="form-control" placeholder="Username">
                            <input name="pass" type="password" class="form-control" placeholder="Password" id="pass">
                            <button type="submit" class="btn btn-default"><span aria-hidden=\"true\">&rarr;</span></button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script type="text/javascript">
		head.ready(function() {
			$(document).ready(function(){
				$("#loginForm").submit(function(e) {
                    e.preventDefault();
					var data = "";
					$(this).children("input").each(function(index, element){ if(data != ""){ data += "&"; } data += element.name+"="+element.value; });
					$.ajax({url:'/ajax.php',method:'POST',async:true,dataType:"json",data:"ari=1&"+data,
						complete:function(xhr){
							if(xhr.responseText == 1){
									$(".alert-msg").html("Success");
									$(".alert").addClass("alert-success").removeClass("hidden");
									$("#signin .glyphicon").addClass("glyphicon glyphicon-ok-sign");
									//$("#page").fadeOut(to,function(){ window.location.assign("/user.php"); });
							}
							if(xhr.responseText == 2){
									$(".alert-msg").html("Login failed");
									$(".alert").addClass("alert-danger").removeClass("hidden");
									$("#signin .glyphicon").addClass("glyphicon-exclamation-sign");
							}
						}
					});
                });
			});
		});
	</script>