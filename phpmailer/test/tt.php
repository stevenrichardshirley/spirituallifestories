<html>
<head>
</head>
<body>

<?php
require_once '../class.phpmailer.php';
$mail = new PHPMailer();

  $mail->IsSMTP();
//  $mail->SMTPAuth = false;
  //$mail->SetFrom('naoresponda@mminternet.com.br', 'NaoResponda');
  $mail->AddAddress('betabitsistemas@gmail.com', 'Luiz Carlos');
  $mail->Subject = 'Super teste';
  $mail->MsgHTML('teste de mensagem em <b>HTML</b> !');
  if ($mail->Send())
    echo "Message Sent OK</p>\n";
  else
    print_r($mail);
?>
</body>
</html>
