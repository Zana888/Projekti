<?php
include_once 'include/functions.php';
include_once 'include/connect.php';
sec_session_start();

if(login_check(dbConnect()) == true) {
	include_once('include/navbar.php');
        // Add your protected page content here!
?>

<script>
$('#tickets').addClass("active");

$(document).ready(function($) {

	$(':file').on('fileselect', function(event, numFiles, label) {
			var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;

			if( input.length ) {
					input.val(log);
			} else {
					if( log ) alert(log);
			}
	});
 });

// We can attach the `fileselect` event to all file inputs on the page
$(document).on('change', ':file', function() {
	var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	input.trigger('fileselect', [numFiles, label]);
});

</script>

<body>

<nav class="navbar navbar-default navbar-fixed-top navbar-inverse subMenu">
  <div class="navbar-inner container-fluid container-fluid">
    <ul class="nav navbar-nav">
    	<li id="subMenuNew"><a href="./main.php">Novi nalog</a></li>
	<li id="subMenuMine"><a href="./tickets.php?ticketId=mine">Moji Ticketi</a></li>
	<li id="subMenuAll"><a href="./tickets.php?ticketId=all">Sve</a></li>
	<li id="subMenuOpen"><a href="./tickets.php?ticketId=open">Otvoreno</a></li>
	<li id="subMenuClient" ><a href="./tickets.php?ticketId=woc">Čeka se klijent</a></li>
	<li id="subMenuAgent" ><a href="./tickets.php?ticketId=woa">Čeka se agent</a></li>
	<li id="subMenuClosed"><a href="./tickets.php?ticketId=closed">Zatvoreno</a></li>
    </ul>
  </div>
</nav>

<div id="content">

<?php

if (isset($_POST['updateTicket'])) {
	$ticket = new ticket();
	$ticket->getTicket($_POST['ticketId']);
	$ticket->setAssignedUser($_POST['assignedUser']);
	$ticket->setStatus($_POST['status']);
	$ticket->setGroupId($_POST['groupId']);
	$ticket->setComments($_POST['comments']);
	$ticket->setSubject($_POST['subject']);

	# add note if applicable
	if ($_POST['ticketNote'] != "") {	$ticket->addNote($_POST['ticketNote']); }

	# notify a user when a ticket is assigned to them
	if ($_POST['previousAssignedUser'] != $_POST['assignedUser']) {
		$user = user::withUserName($ticket->getAssignedUser());

		$systemEmail = system::withName('system email');
		$systemSiteUrl = system::withName('siteurl');

		// $message = "You've been assigned a ticket!";
		// $to = $user->getEmail();
		// $subject = "Ticketdesk - Ticket Assignment";
		// $from = $systemEmail->getValue();
		// $body = 'Hi ' .$ticket->getAssignedUser() . ', <br/> <br/>Ticket id: '. $ticket->getId() .
		// 				' has been assigned to you Click <a href="http://' . $systemSiteUrl->getValue() .'/tickets.php?ticketId='. $ticket->getId() .
		// 				'">here</a> to view the ticket<br/>';
		// $headers = "From: " . strip_tags($from) . "\r\n";
		// $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		// mail($to,$subject,$body,$headers);
	}

	if ($ticket->updateTicket()) {
		echo 'Ticket saved!';
		echo '<META http-equiv="refresh" content="0;URL=./main.php">';
	} else {
		echo 'update failed: ' . $ticket->getMysqli()->error;
	}

}
?>

	<div id="displayTickets" class="panel panel-default">
					<div class="panel-heading">Tickets</div>
					<?php isset($_GET['ticketId']) ? $ticketId = $_GET['ticketId'] : $ticketId = null;?>

        	<?php if ($ticketId == 'all') { ?>
        			<script>$("#subMenuAll").addClass("aktivan"); </script>
        	    <?php ticket::displayTickets('all'); ?>
        	<?php } elseif ($ticketId == 'mine') { ?>
        			<script>$("#subMenuMine").addClass("aktivan"); </script>
        			<?php ticket::displayTickets('mine'); ?>
        	<?php } elseif ($ticketId == 'open') { ?>
        			<script>$("#subMenuOpen").addClass("aktivan"); </script>
        			<?php ticket::displayTickets('Open'); ?>
        	<?php }	elseif ($ticketId == 'woa') { ?>
							<script>$("#subMenuAgent").addClass("aktivan"); </script>
							<?php ticket::displayTickets('Ceka se agent'); ?>
						<?php } elseif ($ticketId == 'woc') {  ?>
								<script>$("#subMenuClient").addClass("aktivan"); </script>
								<?php ticket::displayTickets('Ceka se klijent'); ?>
						<?php } elseif ($ticketId == 'closed') { ?>
								<script>$("#subMenuClosed").addClass("active"); </script>
								<?php ticket::displayTickets('Zatvoreno'); ?>
		<?php } else {
        		$ticket = new ticket();
        		if (isset($_POST['ticketId'])) {$ticket->getTicket($_POST['ticketId']);}
        		if (isset($_GET['ticketId'])) {$ticket->getTicket($_GET['ticketId']);}

        		$category = new category();
        		$category->getCategory($ticket->getCategoryId());
        		$subcat = new subCategory();
        		$subcat->getSubCategory($ticket->getSubCategoryId());
        		// display and update notes...
        	?>
			<div class="panel-body">
        			<div class="well">
				<form class="form" method="POST" enctype="multipart/form-data">
					<input type="text" name="ticketId" value="<?php echo ''. $ticket->getId(); ?>" hidden />
					<input type="text" name="previousAssignedUser" value="<?php echo '' . $ticket->getAssignedUser();?>" hidden />
			                <div class="form-group">
			                    <label for="ticketId" class="col-sm-2 control-label">Ticket# </label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="ticketId" type="text" disabled value="<?php echo ''. $ticket->getId();?> " />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="ClientId" class="col-sm-2 control-label">Klijent# </label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="ClientId" type="text"  value="<?php echo ''. $ticket->getClientId(); ?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="subject" class="col-sm-2 control-label">Predmet:</label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="subject" type="text"  value="<?php echo ''. $ticket->getSubject(); ?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="comments" class="col-sm-2 control-label">Opis:</label>
			                    <div class="col-sm-10">
			                    	<textarea class="form-control verticalonly" name="comments" type="text" ><?php echo ''. $ticket->getComments(); ?> </textarea>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="user" class="col-sm-2 control-label">Kreirano od strane:</label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="user" disabled type="text"  value="<?php echo ''. $ticket->getUser(); ?>" />
			                    </div>
			                </div>


			                <div class="form-group">
			                    <label for="category" class="col-sm-2 control-label">Kategorija:</label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="category" disabled type="text"  value="<?php echo ''. $category->getName(); ?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="subCategory" class="col-sm-2 control-label">Podkategorija:</label>
			                    <div class="col-sm-10">
			                    	<input class="form-control" name="subCategory" disabled type="text"  value=" <?php echo ''. $subcat->getName(); ?>" />
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="status" class="col-sm-2 control-label">Status:</label>
			                    <div class="col-sm-10">
			                        <select class="form-control" name="status">
			                        	<option value="<?php echo ''. $ticket->getStatus(); ?>"><?php echo ''. $ticket->getStatus(); ?> </option>
					        			<!-- komentariši -->
			                        	<?php if ($ticket->getStatus()!='Closed') { ?><!-- komentari -->

					        			<option value="Closed">Zatvoreno</option>
					        			<option value="Open">Otvoreno</option>
					        			<option value="Waiting on Client">Čeka se klijent</option>
					        			<option value="Waiting on Agent">Čeka se agent</option>
					        			<option value="Waiting on 3rd Party">Čeka se treće strane</option> 
			        		 
			                        	<?php } ?>
		        			</select>
			                    </div>
			                </div>

			                <div class="form-group">
			                    <label for="ticketNote" class="col-sm-2 control-label">Dodaj dopis:</label>
			                    <div class="col-sm-10">
			                        <textarea class="form-control" rows="5" name="ticketNote" id="ticketNote"></textarea>
			                    </div>
			                </div>
 <?php if($_SESSION['usertype']=='Administrator'){ ?>
			                <div class="form-group">
			                   <label for="group" class="col-sm-2 control-label">Dodijeljena grupa:</label>
			                    <div class="col-sm-10">
			        		<select class="form-control" name="groupId">
			        			<?php $department = department::withId($ticket->getGroupId()); ?>
			        			<option value="<?php echo $department->getId(); ?> "> <?php echo ''. $department->getName(); ?> </option>
			   				<?php department::displayDepartmentsOptionList(); ?>

			        		</select>
			                    </div>
			                </div>

 <?php }else{ ?>
						
					       <div class="form-group">
			                    <label for="group" class="col-sm-2 control-label">Dodijeljena grupa:</label>
			                    <div class="col-sm-10">
			        		<select class="form-control" name="groupId">
			        			<?php $department = department::withId($ticket->getGroupId()); ?>
			        			<option value="<?php echo $department->getId(); ?> "> <?php echo ''. $department->getName(); ?> </option>
			   			 
			        		</select>
			                    </div>
			                </div>	  

 <?php } ?>
 <?php if($_SESSION['usertype']=='Administrator'){ ?>
			                			<div class="form-group">
												<label for="assignedUser" class="col-sm-2 control-label">Dodijeljeni korisnik:</label>
												<div class="col-sm-10">
													<select class="form-control" name="assignedUser">
														<option value="<?php echo ''. $ticket->getAssignedUser();?> "> <?php echo ''. $ticket->getAssignedUser();?></option>
														<?php user::displayUserOptionList(); ?>
													</select>
												</div>
											</div>
 <?php }else{ ?>

 					<div class="form-group">
												<label for="assignedUser" class="col-sm-2 control-label">Dodijeljeni korisnik:</label>
												<div class="col-sm-10">
												   <select class="form-control" name="assignedUser">
														<option value="<?php echo ''. $ticket->getAssignedUser();?> "> <?php echo ''. $ticket->getAssignedUser();?></option> 
													</select>
												</div>
											</div>
 <?php } ?>
											<div class="form-group">
												<label for="comments" class="col-sm-2 control-label">Upload</label>
													<div class="col-sm-10">
														<div class="input-group">
															<input type="text" class="form-control" readonly>
								                <label class="input-group-btn">
								                    <span class="btn btn-default">
								                        Pregledaj...<input id="fileToUpload" name="fileToUpload" type="file" style="display: none;" multiple>
								                    </span>
								                </label>
								            </div>
													</div>
			                </div>
											<div class="form-group">
												 <label for="comments" class="col-sm-2 control-label">Prilozi</label>
												 <div class="col-sm-10"><?php $ticket->linkAttachments(); ?></div>
											</div>

											<div class="form-group row">
									      <div class="offset-sm-2 col-sm-10">
									        <button name="updateTicket" class="btn btn-primary" type="submit">Ažuriraj</button>
									      </div>
									    </div>



	        		</form>
	        		</div>
	        	</div>
	        	<?php $ticket->getNotes();?>
		<?php }	?>
        </div>

</div>
</body>

<?php
// end protected content
} else {
        echo 'You are not authorized to access this page redirecting you to the <a href="./index.php">login page</a>.';
        echo '<META http-equiv="refresh" content="2;URL=./index.php">';
}

?> 

<script type="text/javascript" src="js/jquery.min.js"></script>

 <script type="text/javascript">
	
	$(".btnrefer").on("click", function(){
		var id = $(this).data("id");
		var assigngroup = document.getElementById('assigngroup'+id).value;
		// alert(id); 
		$(".modal-body #ticketid").val(id);
		$(".modal-body #assigngroup").val(assigngroup);
	});

	$(".btnview").on("click", function(){
		var id = $(this).data("id");
		// alert(id); 
		 $.ajax({
		 	type : "POST",
		 	url : "lastupdate.php",
		 	datatype : "text",
		 	data: {ticketid : id},
		 	success: function(data){

		 		$("#modalView").html(data); 

		 	}
		 })
	});
</script>

