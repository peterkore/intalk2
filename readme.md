## Feladat:
MVC struktúrával rendelkező php alkalmazás routolási feladatainak megoldása.

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
cd ./webshop
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
php bin/create_product TERMÉKNÉV MENNYISÉG
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
A router osztály úgy került megvalósításra, hogy az ne igényeljen külön konfigurációs állományt a meghívásra kerülő kontroller osztály beazonosításához. Továbbá rugalmasan kezeli a Controllers osztály alatt esetlegesen előforduló többszintű könyvtárstruktúrát. A router funkcionalitás használatához csupán néhány szabályt kell megjegyeznünk a kódolás során.

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


vagy (egy vagy több directory is lehet a controllers könyvtár alatt)

```console
http://domain.com/DIRECTORY/CONTROLLER/METHOD/PARAM
```
*Ebben az esetben is a fent részletezett elnevezési konvenciót használjuk.*

1. Az elérési út első tagja a controllers könyvtár alatti könyvtárat azonosítja
    * pl. /src/Controllers/**Admin** könyvtár
2. Az elérési út második tagja a controller osztályt azonosítja.
    * Ebben az esetben a controller osztályokat /src/Controllers/Admin alatt helyezzük el
    * pl. a controllers könyvtár alatti Admin könyvtárban lévő **OrderController** osztály elérési útja a /src/Controllers/Admin/**order** 
3. Az elérési út harmadik tagja a controller osztályon belüli metódust azonosítja
    * pl. az OrderController **view** metódusa az /src/Controllers/Admin/order/**view**/ elérési úton érhető el
4. Az elérési út negyedik tagja az URL path további részeit (paramétereit) tartalmazza
    * pl. az OrderController view metódusának az 1-es idéjű rendelés elérési útja az /src/Controllers/Admin/order/view/**1**


**Megjegyzés: A router jelenleg nem kezeli le azt az esetet, ha a Controllers könyvtáron belül létrehozunk pl. egy Admin könyvtárat, valamint ezzel párhuzamosan, szintén a Controllers könyvtáron belül egy AdminControllers kontroller osztályt.**

### Hogyan működik
A webszerver a .htaccess állományban beállítottaknak megfelelően minden kérést a public könyvtárban található index.php-hoz irányít. Az index.php meghívja a Router.php dispatch() metódusát, ami gondoskodik a megfelelő controller osztály példányosításáról, valamint a megfelő metódus meghívásáról. 

Példa URL-ek a Router működésére:

| URL                                       | Könyvtár (ha van a Controllesr könyvtár alatt) | Controller osztály   | Metódus | Paraméter    | Renderelt View            |
| ----------------------------------------- | ---------------------------------------------- |--------------------- | ------- | ------------ | ------------------------- |
| http://domain.com/                        | -                                              | IndexController      | index   | -            | index.php                 |
| http://domain.com/products                | -                                              | ProductsController   | index   | -            | products.php              |
| http://domain.com/product/view/1          | -                                              | ProductController    | view    | termék id    | product.php               |
| http://domain.com/category/show/1         | -                                              | CategoryController   | show    | kategória id | category/show.php         |
| http://domain.com/cart                    | -                                              | CartController       | index   | -            | cart.php                  |
| http://domain.com/cart/checkout           | -                                              | CartController       | checkout| -            | cart/checkout.php         |
| http://domain.com/order                   | -                                              | OrderController      | index   | -            | order/index.php           |
| http://domain.com/order/show/12           | -                                              | OrderController      | show    | rendelés id  | order/show.php            |
| http://domain.com/login                   | -                                              | LoginController      | index   | -            | login.php                 |
| http://domain.com/Admin/dashboard         | Admin                                          | DashboardController  | index   | -            | Admin/dashboard.php       |
| http://domain.com/Admin/products          | Admin                                          | ProductsController   | index   | -            | Admin/products.php        |
| http://domain.com/Admin/product/new       | Admin                                          | ProductController    | new     | -            | Admin/product_edit.php    |
| http://domain.com/Admin/product/edit/1    | Admin                                          | ProductController    | edit    | termék id    | Admin/product_edit.php    |
| http://domain.com/Admin/orders            | Admin                                          | OrdersController     | index   | -            | Admin/orders.php          |
| http://domain.com/Admin/order/view/1      | Admin                                          | OrderController      | view    | rendelés id  | Admin/order_view.php      |
| http://domain.com/Admin/categories        | Admin                                          | CategoriesController | index   | -            | Admin/categories.php      |
| http://domain.com/Admin/categories/create | Admin                                          | CategoriesController | create  | -            | Admin/category_create.php |
| http://domain.com/Admin/categories/view/1 | Admin                                          | CategoriesController | view    | kategória id | Admin/category_view.php   |
| http://domain.com/Admin/categories/edit/1 | Admin                                          | CategoriesController | edit    | kategória id | Admin/category_edit.php   |
| http://domain.com/Admin/users             | Admin                                          | UsersController      | index   | -            | Admin/users.php           |
| http://domain.com/Admin/users/view/1      | Admin                                          | UsersController      | view    | user id      | Admin/user_view.php       |
| http://domain.com/Admin/users/edit/1      | Admin                                          | UsersController      | edit    | user id      | Admin/user_edit.php       |
| http://domain.com/Admin/users/new         | Admin                                          | UsersController      | new     | -            | Admin/user_edit.php       |


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

### Publikus tartalmak elhelyezése
Publikus tartalmak például css, js fájlok stb. elhelyezésére a /public/... könyvtárat tudjátok igénybevenni. 

*Happy coding!* 😁