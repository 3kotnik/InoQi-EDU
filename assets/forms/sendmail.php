php
if ($_SERVER[REQUEST_METHOD] == POST) {
     Collect and sanitize form data
    $parent_name = htmlspecialchars($_POST['parent_name']  '');
    $child_name = htmlspecialchars($_POST['child_name']  '');
    $address = htmlspecialchars($_POST['address']  '');
    $phone = htmlspecialchars($_POST['phone']  '');
    $email = htmlspecialchars($_POST['email']  '');
    $workshop = htmlspecialchars($_POST['workshop']  '');
    $age = htmlspecialchars($_POST['age']  '');
    $newsletter = isset($_POST['newsletter'])  Da  Ne;
    $privacy_agreement = isset($_POST['privacy_agreement'])  Da  Ne;

     Validate required fields
    if (empty($parent_name)  empty($child_name)  empty($address)  
        empty($phone)  empty($email)  empty($workshop)  empty($age)) {
        echo Napaka Vsa obvezna polja morajo biti izpolnjena.;
        exit;
    }

     Format the email body
    $to = info@inoqi.org;
    $subject = Nova prijava na delavnico  . $workshop;
    
    $body = === PRIJAVA NA DELAVNICO ===nn;
    $body .= PODATKI O PRIJAVIn;
    $body .= Delavnica  . $workshop . n;
    $body .= Starostna skupina  . $age . nn;
    
    $body .= PODATKI O STARŠUSKRBNIKUn;
    $body .= Ime in priimek  . $parent_name . n;
    $body .= Email  . $email . n;
    $body .= Telefon  . $phone . nn;
    
    $body .= PODATKI O OTROKUn;
    $body .= Ime in priimek  . $child_name . n;
    $body .= Naslov  . $address . nn;
    
    $body .= DODATNE INFORMACIJEn;
    $body .= Želi prejemati novičnik  . $newsletter . n;
    $body .= Strinja se z obdelavo podatkov  . $privacy_agreement . n;
    $body .= Datum prijave  . date(d.m.Y Hi) . n;

     Set email headers
    $headers = From InoQi Prijavnica no-reply@inoqi.orgrn;
    $headers .= Reply-To  . $email . rn;
    $headers .= Content-Type textplain; charset=UTF-8rn;

     Attempt to send the email
    if (mail($to, $subject, $body, $headers)) {
         Success message
        echo html
                head
                    meta charset='UTF-8'
                    meta name='viewport' content='width=device-width, initial-scale=1.0'
                    titlePrijava uspešna - InoQi EDUtitle
                    link href='httpsfonts.googleapis.comcss2family=Robotowght@400;500&display=swap' rel='stylesheet'
                    style
                        body {
                            font-family 'Roboto', sans-serif;
                            background-color #f5f5f5;
                            color #212121;
                            display flex;
                            flex-direction column;
                            align-items center;
                            justify-content center;
                            min-height 100vh;
                            text-align center;
                            padding 20px;
                        }
                        .success-card {
                            background-color #ffffff;
                            border-radius 8px;
                            box-shadow 0 4px 12px rgba(0, 0, 0, 0.1);
                            padding 30px;
                            max-width 500px;
                            width 100%;
                        }
                        h1 {
                            color #4CAF50;
                            margin-bottom 20px;
                        }
                        p {
                            margin-bottom 15px;
                            line-height 1.5;
                        }
                        .button {
                            display inline-block;
                            background-color #6200ee;
                            color white;
                            text-decoration none;
                            padding 12px 24px;
                            border-radius 4px;
                            margin-top 20px;
                            font-weight 500;
                            transition background-color 0.3s;
                        }
                        .buttonhover {
                            background-color #3700b3;
                        }
                    style
                head
                body
                    div class='success-card'
                        h1Prijava uspešno poslana!h1
                        pHvala za prijavo na delavnico strong$workshopstrong.p
                        pNa vaš email naslov strong$emailstrong boste prejeli potrditev prijave in nadaljnja navodila.p
                        pV primeru vprašanj nas lahko kontaktirate na stronginfo@inoqi.orgstrong.p
                        a href='index.html' class='button'Nazaj na domačo strana
                    div
                body
            html;
            
         Optional Log the submission to a file or database
         file_put_contents('submissions.log', date('Y-m-d His') .  - $email - $workshopn, FILE_APPEND);
    } else {
         Error message
        echo html
                head
                    meta charset='UTF-8'
                    meta name='viewport' content='width=device-width, initial-scale=1.0'
                    titleNapaka pri prijavi - InoQi EDUtitle
                    link href='httpsfonts.googleapis.comcss2family=Robotowght@400;500&display=swap' rel='stylesheet'
                    style
                        body {
                            font-family 'Roboto', sans-serif;
                            background-color #f5f5f5;
                            color #212121;
                            display flex;
                            flex-direction column;
                            align-items center;
                            justify-content center;
                            min-height 100vh;
                            text-align center;
                            padding 20px;
                        }
                        .error-card {
                            background-color #ffffff;
                            border-radius 8px;
                            box-shadow 0 4px 12px rgba(0, 0, 0, 0.1);
                            padding 30px;
                            max-width 500px;
                            width 100%;
                        }
                        h1 {
                            color #F44336;
                            margin-bottom 20px;
                        }
                        p {
                            margin-bottom 15px;
                            line-height 1.5;
                        }
                        .button {
                            display inline-block;
                            background-color #6200ee;
                            color white;
                            text-decoration none;
                            padding 12px 24px;
                            border-radius 4px;
                            margin-top 20px;
                            font-weight 500;
                            transition background-color 0.3s;
                        }
                        .buttonhover {
                            background-color #3700b3;
                        }
                    style
                head
                body
                    div class='error-card'
                        h1Napaka pri pošiljanju prijaveh1
                        pŽal je prišlo do napake pri pošiljanju vaše prijave.p
                        pProsimo, poskusite znova ali nas kontaktirajte neposredno na stronginfo@inoqi.orgstrong.p
                        a href='javascripthistory.back()' class='button'Nazaj na obrazeca
                    div
                body
            html;
    }
} else {
     If someone tries to access this file directly
    header(Location index.html);
    exit;
}
