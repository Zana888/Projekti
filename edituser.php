<?php
include_once 'include/functions.php';
include_once 'include/connect.php';
include_once 'include/user.php';
include_once 'include/updateuser.php';
include_once 'include/functions.php';
sec_session_start();

if(login_check(dbConnect()) == true) {
	include_once('include/navbar.php'); 
	$user = new user();

	$id = isset($_GET['id']) ? $_GET['id'] : 0;


}
?> 
   <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>

 
		    <div class="panel panel-default" style="margin:2em; margin-top: 90px;">
			  <div class="panel-heading">Uredi nalog korisnika</div>
				<div class="panel-body">
					
					<?php
					if (!empty($error_msg)) {
						echo $error_msg;
					}
					if (!empty($success)) {
						echo $success;
					}
					?>
					<div id="regForm">
					  <div class="col-md-6">
						<form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" method="post" name="registration_form"> 
							<?php $user->editUSer($id); ?>

							<input type="button" name="registerBtn" id="registerBtn" class="btn btn-primary" value="Save" onclick="return regformhash(this.form, this.form.username, this.form.email, this.form.password, this.form.confirmpwd,this.form.id,this.form.groupId);" /> 
						</form> 
					 </div>
					 <div class="col-md-6"> 
						<ul>
							<li>Korisnička imena mogu sadržavati samo cifre, velika i mala slova i donje crte!</li>
							<li>Email-poruke moraju imati važeći format e-pošte!</li>
							<li>Lozinke moraju imati najmanje 6 znakova!</li>
							<li>Lozinke moraju sadržavati:
								<ul>
									<li>Najmanje jedno veliko slovo (A..Ž)</li>
									<li>Najmanje jedno malo slovo (a..ž)</li>
									<li>Najmanje jedan broj (0..9)</li>
								</ul>
							</li>
							<li>Vaša lozinka i potvrda moraju se tačno podudarati!</li>
						</ul>
					 </div>
					 <div class="col-md-12">
					 	<table class="table table-bordered">
					 		<thead>
					 			<th>UserID</th>
					 			<th>Korisničko ime</th>
					 			<th>Email adresa</th> 
					 			<th>Odjel</th> 
					 			<th>Podrška</th>
					 		</thead>
					 		<tbody>
					 			<?php 
					 			  $user->loadUser();
					 			?>
					 		</tbody>
					 	</table>
					 </div>
					</div>
				</div>
			</div> 
 