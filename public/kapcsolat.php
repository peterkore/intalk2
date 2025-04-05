<?php

file_put_contents('debug.log', "PHPMailer-es kapcsolat.php fut!\n", FILE_APPEND);

// Hiba megjelenítés fejlesztéshez
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader betöltése (a vendor mappa egy szinttel feljebb van a /public mappából nézve)
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// JSON választ fogunk visszaküldeni
header('Content-Type: application/json');

$response = ['siker' => false]; // alapértelmezett válasz

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $uzenet = isset($_POST['uzenet']) ? trim($_POST['uzenet']) : '';

    if (!empty($email) && !empty($uzenet)) {
        $phpmailer = new PHPMailer(true);

        try {
            // SMTP beállítások (Mailtrap)
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = 'df0558ec9e27d5';
            $phpmailer->Password = 'c5ddaf0fc7f3d3'; 
            $phpmailer->CharSet = 'UTF-8';

            $phpmailer->setFrom($email, 'Vendég');
            $phpmailer->addAddress('csapatn@kutyawebshop.hu', 'GDE 25 Team');
            $phpmailer->Subject = 'Kutyawebshop Üzenet';

            $phpmailer-> isHTML(true);
            $phpmailer->Body = nl2br(htmlspecialchars($uzenet));

            $phpmailer->send();
            $response['siker'] = true;
            
        } catch (Exception $e) {
            $response['hiba'] = 'Email küldés sikertelen: ' . $phpmailer->ErrorInfo;
        }
    } else {
        $response['hiba'] = 'Hiányzó mezők';
    }
} else {
    $response['hiba'] = 'Érvénytelen kérés';
}

echo json_encode($response);

