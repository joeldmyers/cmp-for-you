<div class="container">
     <div class=" form-container row margin-top-40">  
     <div class="col-md-4"></div>  
        <div id="loginbox" class="col-md-4" style="background-color:#fff; padding:55px 20px;">                    
                     <img src="images/avatar_2x.png" class="profile-img-card" id="profile-img"> 
                        <form id="loginform" class="form-horizontal" role="form" style="padding:20px 40px;" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="email" value="" placeholder="Email">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                                    </div>
                 <div style="float:right; font-size:12px; position: relative;"><a href="#">Forgot password?</a></div>
                 <span style="font-size:12px;">Not a member? <a href="sign-up">Sign Up</a></span>
                                 
                                 <input type="submit" name="login" class="btn-primary signup-btn" value="Login" />
                            </form>     
        </div> 
        <div class="col-md-4"></div><div class="clearfix"></div>
    </div></div></div>