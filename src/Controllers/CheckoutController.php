<?php

class CheckoutController extends Controller
{
    public function index()
    {
        // Checkout weboldal betoltese
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            header("Location: /cart");
            exit;
        }

        $this->view('checkout/index', ['cart' => $cart]);
    }

    public function process()
    {
        // Felhasznalo toltse ki a szallitas es szamla cimet, telefonszamot, email cimet
        $name = $_POST['name'] ?? '';
        $deliveryAddress = $_POST['delivery_address'] ?? '';
        $billingAddress = $_POST['billing_address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $cart = $_SESSION['cart'] ?? [];

        // Ellenorzes, hogy minden mezot kitoltottak-e
        if (empty($name) || empty($deliveryAddress) || empty($phone) || empty($email) || empty($cart)) {
            $_SESSION['error'] = "Kerjuk minden kotelezo mezot toltson ki.";
            header("Location: /checkout");
            exit;
        }

        // Egyedi rendeles szam letrehozasa
        $orderNumber = uniqid('ORDER-');

        // Rendeles mentese
        $orderModel = $this->model('Order');
        $orderId = $orderModel->create([
            'order_number' => $orderNumber,
            'name' => $name,
            'delivery_address' => $deliveryAddress,
            'billing_address' => $billingAddress,
            'phone' => $phone,
            'email' => $email,
            'total' => $this->calculateTotal($cart),
        ]);

        // Rendeles tetelek mentese
        // Feltetelezve, hogy a rendeles tetelek egy kulon modelben vannak
        $orderItemModel = $this->model('OrderItem');
        foreach ($cart as $item) {
            $orderItemModel->create([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Visszaigazolo email kuldese
        $this->sendConfirmationEmail($email, $orderNumber, $cart);

        // Kosar kiurites
        unset($_SESSION['cart']);

        // Sikeres rendelés üzenet
        $_SESSION['success'] = "Rendelese sikeresen rogzitesre kerult. Rendeles szama: $orderNumber";

        // Atiranyitas a kosar oldalra
        header("Location: /thank-you?order=" . urlencode($orderNumber));
        exit;
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function sendConfirmationEmail($to, $orderNumber, $cart)
    {
        $subject = "Rendelese jovahagyasra kerult - $orderNumber";
        $message = "Koszonjuk a rendelest!\n\nRendeles szama: $orderNumber\n\nItems:\n";

        foreach ($cart as $item) {
            $message .= "- {$item['name']} x {$item['quantity']} ({$item['price']}$ each)\n";
        }

        $message .= "\nEresitunk a szallitasrol es a fizetesi modrol.\n\nKoszonjuk, hogy minket valasztott!\n\nPAWsome kisallat wegshop csapata";

        mail($to, $subject, $message);
    }
}
