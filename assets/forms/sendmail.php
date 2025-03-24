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
    $current_date = date("d.m.Y H:i");

    // Validate required fields
    if (empty($parent_name) || empty($child_name) || empty($address) || 
        empty($phone) || empty($email) || empty($workshop) || empty($age)) {
        echo "Napaka: Vsa obvezna polja morajo biti izpolnjena.";
        exit;
    }

    // Format the email body
    $to = "info@inoqi.org";
    $subject = "Nova prijava na delavnico: " . $workshop;
    
    $body = "=== PRIJAVA NA DELAVNICO: " . $workshop . " ===\n\n";
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
    $body .= "Datum prijave: " . $current_date . "\n";

    // Set email headers
    $headers = "From: InoQi Prijavnica <no-reply@inoqi.org>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Generate CSV file
    try {
        // Create a filename based on child's name (remove whitespaces)
        $filename = preg_replace('/\s+/', '', $child_name) . '.csv';
        $filepath = 'prijave/' . $filename;
        
        // Ensure directory exists
        if (!is_dir('prijave')) {
            mkdir('prijave', 0755, true);
        }
        
        // Check if the file exists to determine if we need headers
        $file_exists = file_exists($filepath);
        
        // Open the file for writing (append mode)
        $csvFile = fopen($filepath, 'a');
        
        // Add headers if file is new
        if (!$file_exists) {
            $headers_row = [
                'Datum prijave', 
                'Delavnica', 
                'Starostna skupina',
                'Ime in priimek otroka', 
                'Naslov',
                'Ime in priimek starša', 
                'Email starša', 
                'Telefon starša',
                'Novičnik', 
                'GDPR soglasje'
            ];
            fputcsv($csvFile, $headers_row);
        }
        
        // Add data row
        $data_row = [
            $current_date,
            $workshop,
            $age,
            $child_name,
            $address,
            $parent_name,
            $email,
            $phone,
            $newsletter,
            $privacy_agreement
        ];
        fputcsv($csvFile, $data_row);
        
        // Close the file
        fclose($csvFile);
    } catch (Exception $e) {
        // Log error but continue to send email
        error_log("CSV creation failed: " . $e->getMessage());
    }

    // Send email to admin
    $admin_email_sent = mail($to, $subject, $body, $headers);
    
    // Send auto-reply to the user
    $user_to = $email;
    $user_subject = "Prejeli smo vašo prijavo na delavnico " . $workshop;
    
    $user_body = "Pozdravljeni,\n\n";
    $user_body .= "prejeli smo vašo prijavo na delavnico " . $workshop . ".\n";
    $user_body .= "V kratkem vas bomo obvestili o možnih terminih.\n\n";
    $user_body .= "Hvala za zaupanje,\n";
    $user_body .= "vaša InoQi EDU ekipa";
    
    $user_headers = "From: InoQi EDU <info@inoqi.org>\r\n";
    $user_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    $user_email_sent = mail($user_to, $user_subject, $user_body, $user_headers);

    // Provide response based on admin mail success (user mail is a nice-to-have)
    if ($admin_email_sent) {
        echo "Prijava je bila uspešno poslana!";
    } else {
        echo "Napaka pri pošiljanju prijave.";
    }
} else {
    // If someone tries to access this file directly
    header("Location: index.html");
    exit;
}
?>