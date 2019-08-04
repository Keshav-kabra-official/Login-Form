<!-- ------------------------------------------- PHP CODE --------------------------------------------- -->
<?php
	$msg = ''; //Variable for showing email-status or form-status

	if(filter_has_var(INPUT_POST, 'submit')){

		$con = mysqli_connect('localhost', 'root');
		mysqli_select_db($con, 'connectForm');

		if(mysqli_connect_errno()){
			$msg = "DB-Failed to load !<br>".mysqli_connect_errno();
		}

		/* Taking form-entries to vars */
		$name = mysqli_real_escape_string($con, $_POST['name']); //escaping harmful code
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$address = mysqli_real_escape_string($con, $_POST['address']);
		$phone = mysqli_real_escape_string($con, $_POST['phone_no']);
		$message = mysqli_real_escape_string($con, $_POST['message']);

		/* *********************** DATABASE PROCESS ************************ */
		if(!empty($name) && !empty($email) && !empty($address) && !empty($phone)) {
			$q = "INSERT INTO user(email,name,address,phone,message) VALUES ('$email','$name','$address','$phone','$message')";

			$status = mysqli_query($con, $q);
			mysqli_close($con);
			if($status == 0){
				$msg = "Dear ".ucwords($name).", you are already subscribed !!!";
			}


			/* *********************** EMAIL PROCESS *********************** */
			if($status == 1){ 	//email for new-user only
				require 'php-mailer-master/php-mailer-master/PHPMailerAutoload.php'; //ext file for sending mails

				//email to host
				$mail = new PHPMailer;
				$mail->isSMTP(); 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'keshavXampp88@gmail.com';
				$mail->Password = 'Pas$Word@id_4<for>XamPP-32';
				$mail->SMTPSecure = 'tls';
				$mail->Port = 587;
				////////////////////////////////////////////////////////////////////
				$mail->setFrom('keshavXampp88@gmail.com', 'Localhost Server'); //sender
				$mail->addAddress('keshavkabra118@gmail.com', 'Keshav Kabra'); // Add a recipient
				//$mail->addAddress('keshavkabra10p11@gmail.com', 'Keshav Kabra'); // cc for emails- you can add more
				///////////////////////////////////////////////////////////////////
				$mail->isHTML(true);
				//////////////////////////////////////////////////////////////////
				$mail->Subject = 'New Contact Request';
				$mail->Body    = '<h1>Contact Request </h1>
									<h3>Name</h3><p>'.$name.'</p>
									<h3>Email</h3><p>'.$email.'</p>
									<h3>Address</h3><p>'.$address.'</p>
									<h3>Phone No.</h3><p>'.$phone.'</p>
									<h3>Message</h3><p>'.$message.'</p> ';

				/* To the subscriber */
				$mail1 = new PHPMailer;
				$mail1->isSMTP(); 
				$mail1->Host = 'smtp.gmail.com';
				$mail1->SMTPAuth = true;
				$mail1->Username = 'keshavXampp88@gmail.com';
				$mail1->Password = 'Pas$Word@id_4<for>XamPP-32';
				$mail1->SMTPSecure = 'tls';
				$mail1->Port = 587;
				////////////////////////////////////////////////////////////////////
				$mail1->setFrom('keshavXampp88@gmail.com', 'Localhost Server'); //sender
				$mail1->addAddress($email); // Add a recipient
				///////////////////////////////////////////////////////////////////
				$mail1->isHTML(true);
				//////////////////////////////////////////////////////////////////
				$mail1->Subject = 'You were subscribed';
				$mail1->Body    = '<h1>Subscription Details : </h1>
									<h3>Name</h3><p>'.$name.'</p>
									<h3>Email</h3><p>'.$email.'</p>
									<h3>Address</h3><p>'.$address.'</p>
									<h3>Phone No.</h3><p>'.$phone.'</p>
									<h3>Message you sent </h3><p>'.$message.'</p><br>
									<h2> You are now subscribed with Keshav Kabra. </h2>';


				/* CHECKING */
				if(!$mail->send()) {
				    $msg = "Your email can not be sent !"; //failure
				} else {
					$mail1->send();
				    $msg = "Thank you ".ucwords($name)." .You are subscribed with the email : ( ".$email." )"; //success
				}
			}
		}

		else{
			$msg = "please enter all the required fields !"; //form incomplete
		}
	}
?>

<!-- ------------------------------ HTML PART ----------------------------------- -->
<!DOCTYPE html>
<html>
	<head>
		<title>Connect with Us</title>
		<link rel="icon" href="img/core.ico">
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		<script type="text/javascript" src="jquery-3.4.1.js"></script>
		<script type="text/javascript" src="jquery-ui/jquery/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery-ui/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="jquery-ui/jquery/jquery-ui.structure.css">
		<link rel="stylesheet" type="text/css" href="jquery-ui/jquery/jquery-ui.theme.css">
	</head>
	<body>
		<?php if($msg != '') : ?>
				<div><?php echo $msg; ?></div>
			<?php endif; ?>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="theForm" method="post">
			<span class="star">* required fields</span>
			<p>
				<div class="row">
					<label for="name">Name : </label>
					<input type="text" id="name" placeholder="e.g. Keshav Kabra" name="name" value="<?php echo isset($_POST['name']) ? $name : ''; ?>"><span class="star"> *</span> <br><br>
				</div>

				<div class="row">
					<label for="address">Address : </label>
					<input type="text" id="address" placeholder="e.g. Melbourne, Australia" name="address" value="<?php echo isset($_POST['address']) ? $address : ''; ?>"><span class="star"> *</span> <br><br>
				</div>

				<div class="row">
					<label for="email">Email : </label>
					<input type="email" id="email" placeholder="pqr@xyz.com" name="email"  value="<?php echo isset($_POST['email']) ? $email : ''; ?>"><span class="star"> *</span> <br><br>
				</div>
				
				<div class="row">
					<label for="phone_no">Phone : </label>
					<input type="tel" id="phone_no" name="phone_no" value="+91- "><span class="star"> *</span> <br><br>
				</div>
				<div class="row">
					<label for="message">Message : </label>
					<textarea placeholder="Enter your message, if any, here" name="message" value="<?php echo isset($_POST['message']) ? $message : ''; ?>"></textarea> <br><br>
				</div>
				<div>
					<input class="button" type="submit" value="Submit" name="submit">	
				</div>
			</p>
		</form>

		<!-- -------------- JQUERY UI --------------- -->
		<script>
			$(document).ready(function(){

				$("input").focus(function(){
					$(this).css("background", '#F7BBBB');
					$(this).css("color", "black");
				});
				$("input").blur(function(){
					$(this).css("background", "white");
				});

				$("textarea").focus(function(){
					$(this).css("background", "#F7BBBB");
				});
				$("textarea").blur(function(){
					$(this).css("background", "white");
				});

				$("#name").autocomplete({source: ["Keshav", "Steve", "Roger", "Stephen"]});
			});
		</script>

	</body>
</html>