<?php
include_once 'header.php';
include_once('header.php');
include_once('body.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Regisztráció</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>

<div>
	<form method="post">
		<div class="table">
			<div class="table">
				<div class="table">
					<h1>Regisztráció</h1>
					<p>Kérem töltse ki a mezőket</p>
					<table class="table">
                    <thead>
                        <tr>
                        <th>
							<label for="firstname"><b>Keresztnév</b></label>
							<input class="form-control" id="firstname" type="text" name="firstname" required>
						</th>
						<tr>
						<th>
							<label for="lastname"><b>Vezetéknév</b></label>
							<input class="form-control" id="lastname"  type="text" name="lastname" required>
						</th>
                        </tr>
						<tr>
						<th>
							<label for="email"><b>Email cím</b></label>
							<input class="form-control" id="email"  type="email" name="email" required>
						</th>
						</tr>
						<tr>
						<th>
							<label for="phonenumber"><b>Telefonszám</b></label>
							<input class="form-control" id="phonenumber"  type="text" name="phonenumber" required>
						</th>
						</tr>
						<tr>
						<th>
							<label for="password"><b>Jelszó</b></label>
							<input class="form-control" id="password"  type="password" name="password" required>
						</tr>
						</th>
					</thead>
					</table>
					<div>
						<input class="btn btn-primary" type="submit" id="register" name="create" value="Regisztráció">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript">
	$(function(){
		$('#register').click(function(e){

			var valid = this.form.checkValidity();

			if(valid){

			var firstname 	= $('#firstname').val();
			var lastname	= $('#lastname').val();
			var email 		= $('#email').val();
			var phonenumber = $('#phonenumber').val();
			var password 	= $('#password').val();
			
				e.preventDefault();	

				$.ajax({
					type: 'POST',
					url: 'process.php',
					data: {firstname: firstname,lastname: lastname,email: email,phonenumber: phonenumber,password: password},
					success: function(data){
					Swal.fire({
								'title': 'Sikeres adatfelvitel',
								'text': data,
								'type': 'success'
								})
					},
					error: function(data){
						Swal.fire({
								'title': 'Hiba törént',
								'text': 'Hiba történt az adatok felvitelekor.',
								'type': 'error'
								})
					}
				});
			}else{
			}
		});		
	});
</script>
</body>
</html>
<?php
 include_once('footer.php')
?>
