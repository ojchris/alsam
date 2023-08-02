<?php
// Start with PHPMailer class
use PHPMailer\PHPMailer\PHPMailer;

require_once '../vendor/autoload.php';
// create a new object
$mail = new PHPMailer();

$errors = [];
$errorMessage = '';
$successMessage = '';
$secret = 'your site key';

if (!empty($_POST) && isset($_FILES['file'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $comment = $_POST['thecomment'];
    $from_contacto = $email;
    $applicant_name = $firstname . ' ' . $lastname;
    /*  $recaptchaResponse = $_POST['g-recaptcha-response'];
  $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}";
  $verify = json_decode(file_get_contents($recaptchaUrl));
  
  if (!$verify->success) {
    $errors[] = 'Recaptcha failed';
  }
  */
    if (empty($firstname)) {
        $errors[] = 'First name is empty';
    }
    
    if (empty($lastname)) {
        $errors[] = 'Last name is empty';
    }

    if (empty($position)) {
        $errors[] = 'Position is empty';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    if (empty($comment)) {
        $errors[] = 'Comment is empty';
    }
    /* file handling */

    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['file']['name'])));

    $expensions = array("doc", "docx", "pdf");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please ensure resume / CV is PDF, doc or docx file.";
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    if (empty($errors) == true) {
        
    } else {
        print_r($errors);
    }

    /* file handing ends */

    if (!empty($errors)) {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    } else {
        /*
    $toEmail = 'example@example.com';
    $emailSubject = 'New email from ontant form';
    $headers = ['From' => $email, 'Reply-To' => $email, 'Content-type' => 'text/html; charset=iso-8859-1'];
    $bodyParagraphs = ["Name: {$name}", "Email: {$email}", "Message:", $message];
    $body = join(PHP_EOL, $bodyParagraphs);
    if (mail($toEmail, $emailSubject, $body, $headers)) {
      $successMessage = "<p style='color: green;'>Thank you for contacting us :)</p>";
    }
    else {
      $errorMessage = "<p style='color: red;'>Oops, something went wrong. Please try again later</p>";
    }
    */

    move_uploaded_file($file_tmp, "CVuploads/" . $file_name); //The folder where CVs will be saved

        /* PHP Mailer */
        // configure an SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cokpada37@gmail.com';
        $mail->Password = 'mhtiustjfyrduvzo';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('webmaster@alsaminternational.com', 'Alsam International Webmaster');
        $mail->AddReplyTo($from_contacto, $applicant_name);
        $mail->addAddress('info@alsaminternational.com', 'Alsam International Ventures LLC');
        $mail->Subject = 'Message from CV / Resume form - ' . $position;
        // Set HTML 
        $mail->isHTML(TRUE);
        $mail->Body = '<html><p><b>Position:</b> ' . $position . '<hr></p><p><b>Comment:</b><br>' . $comment . '</p><p><b>Name:</b> ' . $firstname . ' ' . $lastname . '</b> with email address: ' . $email . '</p><p><b>CV / Resume filename:</b> ' . $file_name . '</html>';
        $mail->AltBody =  'Position: ' . $position . '<br>Comment:' . $comment . '<br>Name: ' . $firstname . ' ' . $lastname . ' with email address: ' . $email;
        // add attachment // just add the '/path/to/file.pdf', 'filename.pdf'
        $mail->addAttachment('CVuploads/' . $file_name);
        // send the message
        if (!$mail->send()) {
            header('Location: http://www.alsaminternational.com/upload-cv-resume.html');
            exit();
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            header('Location: http://www.alsaminternational.com/contact-email-notice.html');
            exit();
            echo 'Message has been sent';
        }

        /* PHP Mailer Ends*/
    }
} else {
    header('Location: http://www.alsaminternational.com/upload-cv-resume.html');
    exit();
}
?>