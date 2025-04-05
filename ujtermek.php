<?php
include_once 'header.php';
include_once('header.php');
include_once('body.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Új termék felvétel</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Új termék felvétele</h1>
        <form method="post" target="_self">
            <input type="text" name="name" placeholder="Megnevezés" required/>
            <select name="pet" id="pet">
                <option value="dog">Kutya</option>
                <option value="cat">Cica</option>
                <option value="small">Kisemlős</option>
                <option value="bird">Madár</option>
                <option value="fish">Halak</option>
                <option value="reptile">Hüllők</option>
            </select>
            <input type="text" name="function" placeholder="Funkció" required/>
            <div>
            <textarea rows = "8" cols = "60" name = "description" required>
                Termékleírás
            </textarea>
            </div>
            <div>
            Szavatossági idő: 
            <input type="datetime-local" />
            </div>
            <div>
			<label for="myFile">Adjon meg egy képet</label>
			<input type="file" id="myFile" name="filename" required>
            </div>
            <input type="submit">
        </form>
    </body>
</html>

<?php
 include_once('footer.php')
?>