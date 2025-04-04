<?php
$showDog = rand(1, 100) <= 98;
?>

<?php if ($showDog): ?>
<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      const dog = document.createElement('img');

      // Átlátszó háttérrel rendelkező kutya GIF
      dog.src = 'dog.gif';

      // Alap stílus
      dog.style.position = 'fixed';
      dog.style.bottom = '0';
      dog.style.left = '100vw'; // indul a képernyő jobb széléről
      dog.style.height = '100px';
      dog.style.zIndex = '9999';
      dog.style.transition = 'left 10s linear';

      document.body.appendChild(dog);

      // Egy kis delay után elkezd mozogni balra
      setTimeout(() => {
        dog.style.left = '-200px'; // kifut teljesen balra
      }, 100);

      // Eltávolítjuk 10 másodperc után
      setTimeout(() => {
        dog.remove();
      }, 10000);

    }, Math.floor(Math.random() * 5000) + 3000); // 3–8 másodperc delay
  });
</script>
<?php endif; ?>
<?php
$showDog = rand(1, 100) <= 98;
?>

<?php if ($showDog): ?>
<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      const dog = document.createElement('img');

      // Kutya gif útvonala (átlátszó háttérrel)
      dog.src = 'dog.gif';
      dog.style.position = 'fixed';
      dog.style.bottom = '0';
      dog.style.left = '100vw';
      dog.style.height = '100px';
      dog.style.zIndex = '9999';
      dog.style.transition = 'right 10s linear';
      dog.style.transform = 'scaleX(-1)'; // tükrözés, hogy balra nézzen

      document.body.appendChild(dog);

      // Ugatás lejátszása
      /*const bark = new Audio('liheg.mp3'); // állítsd be a helyes útvonalat
      bark.play().catch((e) => {
        console.warn('A böngésző nem engedte automatikusan lejátszani a hangot.');
      });*/

      window.addEventListener('click', () => {
        const bark = new Audio('liheg.mp3');
        bark.play();
      });

      // Elindítjuk a mozgást
      setTimeout(() => {
        dog.style.left = '-200px';
      }, 100);

      // 5 másodperc múlva eltávolítjuk
      setTimeout(() => {
        dog.remove();
      }, 5000);

    }, Math.floor(Math.random() * 5000) + 3000); // 3–8 másodperc várakozás
  });
</script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Csapatunk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" link="style.css">
</head>
<body>

  <div class="container text-center mt-5">
    <h1 class="mb-4">Ismerd meg a projektünket</h1>

    <!-- Kép a csapatról -->
    <img src="Petshop.png" alt="Csapatkép" class="img-fluid rounded shadow mb-4">

    <!-- Szöveg a kép alatt -->
    <div class="card p-4 shadow-sm" >
      <p class="lead">
        <h3>Internetes alkalmazásfejlesztés 2 - GDE</h3>
        <br><h4>Rólunk</h4><br>
        Webáruházunk küldetése, hogy egyszerű, gyors és biztonságos módot kínáljunk kisállat-kiegészítők vásárlására az online térben.
        Célunk egy olyan platform megvalósítása, amely nemcsak felhasználóbarát és átlátható, de technológiailag is megfelel a modern webes elvárásoknak.
        <br>
        Háttér és kontextus
        Weboldalunk egy tanulmányi projekt keretében indult el, amelynek keretében egy működőképes webshopot hozunk létre. A fő fókusz a kisállatok számára kínált kiegészítő termékek árusítása, melyeket a jövőben – a munka mennyiségének és az igényeknek megfelelően – további kategóriákkal bővíthetünk, mint például eledel vagy más háziállatokhoz tartozó termékek.
        <br>
        <br><h4>Céljaink</h4><br>
        <ul>
          <li>Felhasználóink számára egy átlátható, könnyen kezelhető webshopot biztosítani.</li>
          <li>Lehetővé tenni a rendelésleadást, készletellenőrzést és fizetést.</li>
          <li>A rendelési folyamat után elindítani a megfelelő logisztikai lépéseket.</li>
          <li>Adatbiztonság és kiberbiztonság biztosítása a teljes vásárlási folyamat során.</li>
          <li>A fejlesztés minden szakaszában folyamatos tesztelést végzünk, hogy hibamentes, stabil rendszert hozzunk létre.</li>
          <li>És persze... egy jó jegyet kapni! 🙂</li>
        </ul>
        <br><h4>Projekt scope és főbb elemek</h4><br>
        <br><h4>Deliverable    Leírás</h4><br>
        Adatbázis (termékek)    Külön adatbázis a kisállat-kiegészítőknek, később bővíthető új termékekkel.
        Weblap tervezés + MVP    Felhasználóbarát felület kialakítása, minimálisan működő termékkel indulva.
        Rendelési folyamat    Teljes rendelési rendszer, akár „gyors hozzáadás” opcióval.
        Adatvédelem    Vásárlók adatainak biztonságos kezelése, jogi szabályozások betartása (ÁSZF, GDPR, Cookie Law).
        Fizetés és szállítás    Bankkártyás és utánvétes fizetés lehetősége, többféle kiszállítási opció.
        Webes technológiák    PHP, HTML5, CSS, JavaScript, Bootstrap – a modern web alapkövei.
        <br><br>
      </p>
      <a href="#" class="btn btn-secondary btn-sm" role="button">Kapcsolat</a>
    </div>

  </div>
        
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>