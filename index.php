<?php
include_once 'include/connect.php';
include_once 'include/functions.php';
 
sec_session_start();
 
if (login_check(dbConnect()) == true) {
    #$logged = 'in';
    header('location: main.php');
} else {
    $logged = 'out';
}
?>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src="js/bootstrap.min.js"></script>


<script>
function checkSubmit(e, dom)
{
   if(e && e.keyCode == 13)
   {
       var form = document.getElementById("login_form");
       var password = document.getElementById("password");
       formhash(form, password);
      document.forms[0].submit();
   }
}
</script>

<!DOCTYPE html>
<html>
    <head>
        <title>Sigurno prijavljivanje: Prijavite se</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>  
		<div id="loginPanel" class="panel panel-default">
		  <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Ulogujte se!</div>
  			<div class="panel-body">
		        <?php
		        if (isset($_GET['error'])) {
		            echo '<p class="alert alert-danger">Error logging in, please try again.</p>';
		        }
		        ?> 
			<form action="include/process_login.php" method="post" name="login_form" id="login_form" onKeyPress="return checkSubmit(event, this)">
			    <div class="form-group">
			        <label class="col-sm-2 control-label">Korisničko ime</label>
			        <div class="col-sm-10">
				        <input type="text" name="email" id="email" class="form-control" placeholder="user@email.com" aria-describedby="basic-addon1">
				    </div>
			    </div>
			    <div class="form-group">
			        <label class="col-sm-2 control-label">Lozinka</label>
			        <div class="col-sm-10">
			            <input type="password" name="password" id="password"  class="form-control" placeholder="Password" aria-describedby="basic-addon1">
			        </div>
		        </div>
		        
		        <div class="form-group">
		            <div class="col-sm-2"></div>
		            <div class="col-sm-10">
		                <div class="btn-toolbar" style="margin:1em 0 1em -5px;" role="toolbar" aria-label="...">
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" value="Login" id="Login" onclick="formhash(this.form, this.form.password);" class="btn btn-success">Ulogujte se</button>
                            </div>
                            <div class="btn-group" role="group" aria-label="...">
                                
                            </div>
                        </div> 
                    </div> 
                </div> 		

			</form>
			<?php
        if (login_check(dbConnect()) == true) {
                        echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';
 
            echo '<p>Do you want to change user? <a href="include/logout.php">Log out</a>.</p>';
        } else {
                        echo '<p>Currently logged ' . $logged . '.</p>';
                        echo "<p>If you don't have a login, please register";
                }
?>      	</div>
		
		</div>
		<!-- the DIV that will contain the widget -->
<div class="weatherWidget" ></div>

<script>
   window.weatherWidgetConfig =  window.weatherWidgetConfig || [];
   window.weatherWidgetConfig.push({
       selector:".weatherWidget",
       apiKey:"YOUR_API_KEY", //Sign up for your personal key
       location:"Brcko, Bosnia and Herzegovina", //Enter an address
       unitGroup:"metric", //"us" or "metric"
       forecastDays:5, //how many days forecast to show
       title:"Brcko, Bosnia and Herzegovina", //optional title to show in the 
       showTitle:true, 
       showConditions:true
   });
  
   (function() {
   var d = document, s = d.createElement('script');
   s.src = 'https://www.visualcrossing.com/widgets/forecast-simple/weather-forecast-widget-simple.js';
   s.setAttribute('data-timestamp', +new Date());
   (d.head || d.body).appendChild(s);
   })();
</script>	
    </body>
</html>