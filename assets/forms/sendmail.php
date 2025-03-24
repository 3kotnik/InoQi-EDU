<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $parent_name = htmlspecialchars($_POST['parent_name'] ?? '');
    $child_name = htmlspecialchars($_POST['child_name'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $workshop = htmlspecialchars($_POST['workshop'] ?? '');
    $age = htmlspecialchars($_POST['age'] ?? '');
    $newsletter = isset($_POST['newsletter']) ? "Da" : "Ne";
    $privacy_agreement = isset($_POST['privacy_agreement']) ? "Da" : "Ne";

    // Validate required fields
    if (empty($parent_name) || empty($child_name) || empty($address) || 
        empty($phone) || empty($email) || empty($workshop) || empty($age)) {
        echo "Napaka: Vsa obvezna polja morajo biti izpolnjena.";
        exit;
    }

    // Format the email body
    $to = "info@inoqi.org";
    $subject = "Nova prijava na delavnico: " . $workshop;
    
    $body = "=== PRIJAVA NA DELAVNICO ===\n\n";
    $body .= "PODATKI O PRIJAVI:\n";
    $body .= "Delavnica: " . $workshop . "\n";
    $body .= "Starostna skupina: " . $age . "\n\n";
    
    $body .= "PODATKI O STARŠU/SKRBNIKU:\n";
    $body .= "Ime in priimek: " . $parent_name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Telefon: " . $phone . "\n\n";
    
    $body .= "PODATKI O OTROKU:\n";
    $body .= "Ime in priimek: " . $child_name . "\n";
    $body .= "Naslov: " . $address . "\n\n";
    
    $body .= "DODATNE INFORMACIJE:\n";
    $body .= "Želi prejemati novičnik: " . $newsletter . "\n";
    $body .= "Strinja se z obdelavo podatkov: " . $privacy_agreement . "\n";
    $body .= "Datum prijave: " . date("d.m.Y H:i") . "\n";

    // Set email headers
    $headers = "From: InoQi Prijavnica <no-reply@inoqi.org>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Attempt to send the email
    if (mail($to, $subject, $body, $headers)) {
        // Success message
        echo "Prijava je bila uspešno poslana!";
    } else {
        // Error message
        echo "Napaka pri pošiljanju prijave.";
    }
} else {
    // If someone tries to access this file directly
    header("Location: index.html");
    exit;
}
?>