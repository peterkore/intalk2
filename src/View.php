<?php

namespace Webshop;

class View
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = 'Templates';
    }

    /**
     * Renderel egy sablont a megadott adatokkal.
     *
     * @param string $view A sablonfájl neve (például: "index.php")
     * @param array $params A sablonhoz átadott adatok (asszociatív tömb)
     * @return string A renderelt sablon kimenete
     * @throws Exception Ha a sablonfájl nem található
     */
    public function render(string $view, array $params = []): string
    {
        // Összeállítjuk a nézet fájl elérési útvonalát az aktuális könyvtárból, a basePath változó értékéből, illetve és a nézet fájl nevéből
        $viewPath = __DIR__ . DIRECTORY_SEPARATOR . $this->basePath . DIRECTORY_SEPARATOR . $view;

        // Ellenőrizzük, hogy létezik-e a nézet fájl
        if (!file_exists($viewPath)) {
            // Ha nem található a fájl, kivételt dobunk egy konkrét hibaüzenettel
            throw new \Exception("A sablon fájl nem található: $viewPath");
        }

        // Elindítjuk a kimeneti pufferelést, hogy a sablon kimenete ne jelenjen meg azonnal
        ob_start();
        // Biztosítjuk, hogy a puffer ne kerüljön automatikusan kiírásra
        ob_implicit_flush(false);
        // Az asszociatív tömb típusú paraméterekből kinyerjük a változókat oly módon, hogy a tömb kulcsa lesz a változó, míg a tömb értéke a változó értéke. Ütközés esetén felülírás történik
        extract($params, EXTR_OVERWRITE);
        // Betöltjük a nézet fájlt, amely a paramétereket használja
        require $viewPath;
        // Visszatérünk a pufferelt kimenettel, és töröljük a puffert
        return ob_get_clean();
    }
}
