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

if (!empty($_POST)) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['themessage'];
  $from_contacto =  $email;
/*  $recaptchaResponse = $_POST['g-recaptcha-response'];
  $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}";
  $verify = json_decode(file_get_contents($recaptchaUrl));
  
  if (!$verify->success) {
    $errors[] = 'Recaptcha failed';
  }
  */
  if (empty($name)) {
    $errors[] = 'Name is empty';
  }
  if (empty($email)) {
    $errors[] = 'Email is empty';
  }
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email is invalid';
  }
  if (empty($message)) {
    $errors[] = 'Message is empty';
  }

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
$mail->AddReplyTo($from_contacto, $name);
$mail->addAddress('info@alsaminternational.com', 'Alsam International Ventures LLC');
$mail->Subject = 'Message from website contact form - ' . $subject;
// Set HTML 
$mail->isHTML(TRUE);
$mail->Body = '<html><p><b>Subject:</b> ' . $subject . '<hr></p><p><b>The Message:</b><br>' . $message . '</p><p><b>From:</b> ' . $name . '</b> with email address: ' . $email . '</p></html>';
$mail->AltBody =  'Subject: ' . $subject . '<br>The Message:' . $message . '<br>from: ' . $name . 'with email address: ' . $email;
// add attachment // just add the '/path/to/file.pdf', 'filename.pdf'
//$mail->addAttachment('files/'.'REMITANIN.pdf');
// send the message
if(!$mail->send()){
    header('Location: http://www.alsaminternational.com/contact.html');
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
}
else{
    header('Location: http://www.alsaminternational.com/contact.html');
    exit();
}

?> 