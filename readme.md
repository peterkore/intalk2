# Verzió 0.1

## Feladat:
MVC struktúrával rendelkező php alkalmazás routolási feladatainak megoldása. Kérlek titeket, hogy bármilyen módosítás a dokumentációban új verzió szám feltüntetésével, jelen dokumentum alá kerüljön beírásra.

## Telepítés

### A telepítés előtt
Győződj meg arról, hogy az Apache webszerver támogatja a mod_rewrite modult. Engedélyezheted a mod_rewrite modult például ezzel a paranccsal:
```console
sudo a2enmod rewrite
sudo systemctl restart apache2
```
Ha szükésges állítsd be a webszerver konfigurációját, hogy engedélyezze a .htaccess fájlok használatát (például az AllowOverride All beállítás).

```console
<Directory /var/www/PROJEKT_KÖNYVTÁR>
    AllowOverride All
</Directory>
```

### Váltsál át a web könyvtárra
```console
cd /var/www
```
### Clonozzad le a git repot
```console
git clone https://github.com/peterkore/intalk2.git
```

### Lépjél be a könyvtárba (például ha a /var/www alá szeretnéd elhelyezni a porjektet)
```console
cd ./intalk2
```

### Telepítsed a composer csomagokat
```console
composer install
```

### Másold le az .env.example fájlt .env néven és szerkesszed a tartalmát
```console
cp .env.example .env
```

### Módosítsad az .env fájl tartalmát, adjad meg saját rendszered sql hozzáférésének adatait

## Adatbázis kezelése

### Adatbázis séma létrehozása
```console
php bin/doctrine orm:schema-tool:create
```

### Adatbázis séma törlése
```console
php bin/doctrine orm:schema-tool:drop --force
```

### Adatbázis újratöltése
```console
php bin/seed_database
```

A `seed_database` szkript a következő adatokat tölti be az adatbázisba:

#### Kategóriák
- Kutyaeledel
- Macskaeledel
- Felszerelések
- Higiénia
- Kiegészítők

#### Felhasználók
- Admin User (admin@petshop.hu)
- Test User (test@petshop.hu)

#### Termékek
- Royal Canin Adult (Kutyaeledel) - 8999 Ft
- Whiskas Csirke (Macskaeledel) - 399 Ft
- Flexi póráz (Felszerelések) - 4999 Ft
- Macska kaparófa (Felszerelések) - 12999 Ft
- Kutyasampon (Higiénia) - 2499 Ft
- Macska alom (Higiénia) - 3999 Ft
- Kutya fekhely (Kiegészítők) - 6999 Ft
- Macska szállítóbox (Kiegészítők) - 5999 Ft

### Egyedi termék létrehozása
```console
php bin/create_product "Termék neve" --price=1000 --stock=10 --sku="ABC123" --brand="Márka" --model="Modell" --attr="color=red,size=35"
```

### Egyedi kategória létrehozása
```console
php bin/create_category "Kategória neve" --description="Kategória leírása"
```

### Egyedi felhasználó létrehozása
```console
php bin/create_user "Felhasználó neve" "email@example.com" "jelszó"
```

## Termékek létrehozása

### Hozzad létre az adatbázis sémát
```console
php bin/doctrine orm:schema-tool:create
```

### adjál hozzá egy terméket az adatbázishoz
```console
php bin/create_product test3
```
&nbsp;
&nbsp;
## Tesztelés

### Tekintsed meg a Termékek lapot
http://localhost/products/index
### Az 1. azonosítóval rendelkező egyedi terméklap megtekintéséhez
http://localhost/product/view/1

*A webszerver beállításaid függvényében a http://localhost rész változhat*

&nbsp;
## A router működése
A router osztály úgy került megvalósításra, hogy az ne igényeljen külön konfigurációs állományt a meghívásra kerülő kontroller osztály beazonosításához. A router funkcionalitás használatához csupán néhány szabályt kell megjegyeznünk a kódolás során.

A meghívott URL felépítése:

```console
http://domain.com/CONTROLLER/METHOD/PARAM
```

1. Az elérési út első tagja a controller osztályt azonosítja.
    * A controller osztályokat /src/Controllers alatt helyezzük el
    * Nevüket úgy képezzük, hogy az elérési út első tagját kiegészítjük a 'Controller.php' értékkel.
    * A névadásnál figyeljünk arra, hogy az osztály nevét nagybetüvel kezdjük
       * Például ha a /products/ elérési utat adjuk meg, a hozzá tartozó controller osztályt a ProductsController.php néven kell felvennünk.
2. Az elérési út második tagja a controller osztályon belüli metódust azonosítja
    * például ha a ProductsController.php index metódusát szeretnénk meghívni, azt a /products/index cím megadásával tehetjük meg
      * Amennyiben nem adunk meg második tagot az URL-ben, automatikusan az index metódus kerül meghívásra
3. Amennyiben SEO barát URL-eket szeretnénk használni, az URL path további részei átadásra kerülnek a meghivott controller metódusának paramétereként. (lásd ProductController osztály.)

### Hogyan működik
A webszerver a .htaccess állományban beállítottaknak megfelelően minden kérést a public könyvtárban található index.php-hoz irányít. Az index.php meghívja a Router.php dispatch() metódusát, ami gondoskodik a megfelelő controller osztály példányosításáról, valamint a megfelő metódus meghívásáról. A kérésben szereplő URL path első tagja a controller osztályt azonosítja, amig a második tag a hívásra kerülő metódust azonosítására szolgál. Az URL további tagjait SEO barát URL paraméterként értelmezi a rendszer, ami átadásra kerül a meghívott metódus számára.

A http://domain.com/product/view/1 webcím hívása esetén a ProdcutController.php osztájunkba felvett view metódus átveszi a paraméterként megadott 1-es számot az $id változóban
```php
 public function view($id = '')
```
A következő kódblokk elhelyezése esetén, az $id értékét $_GET változó alapján állítjuk be, ha nincsen megadva SEO URL parméter
```php
if(empty($id) && isset($_GET['id'])){
    $id = $_GET['id'];
}
```

A gyökér és a public folder alatt található .htaccess fájlokban meghatározott átírányítások részleteiért lásd: *https://stackoverflow.com/questions/23635746/htaccess-redirect-from-site-root-to-public-folder-hiding-public-in-url*

## Extrák
A szemléltetés kedvéért kialakítottam egy alap MVC struktúrát, valamint az adatbázis kezeléséhez beállításra került a doctrine ORM is az alkalmazásban. Ezek a részek igény szerint cserélhetőek.

### Model
A későbbi felhasználás lehetősége és a tesztelés megkönnyítése érdekében telepítésre került a Doctrine ORM csomagja, amely segítséget jelenthet az adatok kezelésében.

Részletekért lásd: https://www.doctrine-project.org/

Olyan controller osztályok esetében, ahol el szeretnénk érni a model réteget, a controller osztályunkat származtassuk a BaseController osztályból, a Doctrine entityManager elérésének érdekében.
```php
class ProductsController extends BaseController
{
    public function index()
    {
        ...
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        ...
    }
}
```

### View

A teljesség kedvéért kialakításra került egy egyszerű View osztály is, amelynek render() metódusa két paramétert vár.
1. Az src/Templates könyvtár alatt található php alapú template fájl nevét
2. A template számára átadásra kerülő változók értékét tömb formában

Például az alábbi hívás az src/Templates könyvtár alatt található 404.php-t tölti be, amelyen belül elérhetővé válik a $message változó, amelynek aktuális értéke '404 A keresett lap nem található!' lesz.

```php
echo (new View())->render('404.php', [
    'message' => '404 A keresett lap nem található!'
]);
```

*Természetesen ettől eltérő megoldás is alkalmazható pl. twig template használata*

### Publikus tartalmak elhelyezése
Publikus tartalmak például css, js fájlok stb. elhelyezésére a /public/... könyvtárat tudjátok igénybevenni. 

*Happy coding!* 😁