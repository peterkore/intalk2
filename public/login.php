<?php
    session_start();  //user session 
    if(isset($_SESSION['username'])){
        header("Location: welcome.php");  //a következő betöltendő oldal
    }
?>
<?php
    $login = false;
    include('connection.php');
    //leellenőrzöm hogy a POST-ban submit van-e
    if (isset($_POST['submit'])) {  
        //ha igen akkor beállítom a változókat
        $username = $_POST['user'];
        $password = $_POST['pass'];
        //echo $password;
        $sql = "select * from users where username = '$username'or email = '$username'";  
        $result = mysqli_query($conn, $sql);  //lekérdezés adatbázisból
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  //az eredményt asszociativ tömbben adja vissza
        $count = mysqli_num_rows($result);  //visszaadja a sorok számát
        
        if($row){  
            echo $count;
            //ellenőrizzük hogy a mentett és a megadott jelszavak egyeznek 
            if(password_verify($password, $row["password"])){
                $login=true;  //ha igan akkor beengedjük a felhasználót
                session_start();  //elindítjuk a felhasználó session-t

                //kiválasztjuk a felhasználó nevét az adatbázisból
                $sql = "select username from users where username = '$username'or email = '$username'"; 
                //lekérdezzük a felhasználó adatait az adatbázisból    
                $r = mysqli_fetch_array(mysqli_query($conn, $sql), MYSQLI_ASSOC);  

                //a munkamenetben letároljuk a felhasználó nevét
                $_SESSION['username']= $r['username'];
                $_SESSION['loggedin'] = true;
                header("Location: welcome.php");
            }
        }  
        else{  
            echo  '<script>
                        
                        alert("Bejelentkezés sikertelen! Érvénytelen felhasználónév vagy jelszó!")
                        window.location.href = "login.php";
                    </script>';
        }     
    }
    ?>
    <?php 
    include("connection.php");
    ?>
    
<html>
    <head>
        <title>Kisállatbolt</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">

    </head>
    <body>
        <div><img src="kiskutya2.png" style="display: block; margin: 30px auto; width: 15%;"/></div>
        <div id="form">
            <h1 id="heading">Bejelentkezés</h1>
            <form name="form" action="login.php" method="POST" required>
                <label>Adja meg a felhasználónevét vagy email címét: </label>
                <input type="text" id="user" name="user" placeholder="Email cím" required></br></br>
                <label>Adja meg a jelszavát: </label>
                <input type="password" id="pass" name="pass"  placeholder="Jelszó" required></br></br>
                <input type="submit" id="btn" value="Belépés" name = "submit" style="display: block; margin: auto;"/>
            </form>
        </div>
        <script>
            // az isvalid egy JavaScript validációs függvény, a HTML űrlap adatainak ellenőrzésére
            //leellenőrizzük hogy a felhasználó megadta e a nevét vagy az email címét
            function isvalid(){
                var user = document.form.user.value;
                if(user.length==""){
                    alert(" Adjon meg felhasználónevet vagy email címet!");
                    return false;
                }
            }
        </script>
    </body>
</html>