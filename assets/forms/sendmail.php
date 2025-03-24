<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "info@inoqi.org";
    $subject = "Sporočilo";
    $body = "Ime: $name\nE-pošta: $email\nSporočilo:\n$message";
    $headers = "From: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Sporočilo je bilo uspešno poslano!";
    } else {
        echo "Napaka pri pošiljanju sporočila.";
    }
}
?>