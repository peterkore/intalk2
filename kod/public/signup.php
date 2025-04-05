<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("Location: welcome.php");
    }
?>
<?php
    include("connection.php");
    if(isset($_POST['submit'])){
        $username = mysqli_real_escape_string($conn, $_POST['user']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['pass']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpass']);
        
        $sql="select * from users where username='$username'";
        $result = mysqli_query($conn, $sql);
        $count_user = mysqli_num_rows($result);

        $sql="select * from users where email='$email'";
        $result = mysqli_query($conn, $sql);
        $count_email = mysqli_num_rows($result);

        if($count_user == 0 & $count_email==0){
            if($password==$cpassword){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users(username, address, email, password) VALUES('$username', '$address', '$email', '$hash')";
                $result = mysqli_query($conn, $sql);
                if($result){
                    header("Location: login.php");
                }
            }
            else{
                echo '<script>
                    alert("A jelszavak nem egyeznek!");
                    window.location.href = "signup.php";
                </script>';
            }
        }
        else{
            if($count_user>0){
                echo '<script>
                    window.location.href="index.php";
                    alert("Username already exists!!");
                </script>';
            }
            if($count_email>0){
                echo '<script>
                    window.location.href="index.php";
                    alert("Email already exists!!");
                </script>';
            }
        }
        
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kisállatbolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div><img src="kiskutya1.png" style="display: block; margin: 30px auto; width: 15%;"/></div>
      <div id="form">
            <h1 id="heading">Regisztráció</h1><br>
            <form name="form" action="signup.php" method="POST">
                <i class="fa fa-user fa-lg"></i>
                <input type="text" id="user" name="user" placeholder="Adja meg a felhasználónevét" required></br></br>
                <i class="fa-solid fa-home fa-lg"></i>
                <input type="text" id="address" name="address" placeholder="Adja meg a lakcímét" required></br></br>
                <i class="fa-solid fa-envelope fa-lg"></i>
                <input type="email" id="email" name="email" placeholder="Adja meg az email címét" required></br></br>
                <i class="fa-solid fa-lock fa-lg"></i>
                <input type="password" id="pass" name="pass" placeholder="Adja meg a jelszavát" required></br></br>
                <i class="fa-solid fa-lock fa-lg"></i>
                <input type="password" id="cpass" name="cpass" placeholder="Jelszó megerősítés" required></br></br>
                <input type="submit" id="btn" value="Regisztráció" name = "submit" style="display: block; margin: auto;"/>
            </form>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
