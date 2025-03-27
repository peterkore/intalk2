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
        $viewPath = __DIR__ . DIRECTORY_SEPARATOR . $this->basePath . DIRECTORY_SEPARATOR . $view;

        if (!file_exists($viewPath)) {
            throw new \Exception("A sablon fájl nem található: $viewPath");
        }

        // Kinyerjük a paramétereket a sablon környezetébe
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require $viewPath;
        return ob_get_clean();
    }
}
