<?php
    class MailComposer {

        private $mailer;

        // Parametri di configurazione del mailer
        private $username = "";
        private $password = "";

        private $host = "smtp.gmail.com";
        private $port = 465;

        /* Ottengo i dati dal file di impostazioni
            tramite le classe che li gestisce
        */
        function __construct() {

            // Configurazione della libreria mailer
            $this->mailer = new PHPMailer;
            $this->mailer->isSMTP();
            $this->mailer->SMTPDebug = 1; // TODO rimuovere al release
            $this->mailer->CharSet = "text/html; charset=UTF-8;";
            $this->mailer->XMailer = ' ';

            $this->mailer->Debugoutput = 'html';
            $this->mailer->SMTPSecure = "ssl";
            $this->mailer->SMTPAuth = true;

            $this->mailer->Username = $this->username;
            $this->mailer->Password = $this->password;
            $this->mailer->Host = $this->host;
            $this->mailer->Port = $this->port;

            $this->mailer->DKIM_domain = 'fantavikings.it';
            $this->mailer->DKIM_private = './private.key';
            $this->mailer->DKIM_selector = 'phpmailer';
            $this->mailer->DKIM_passphrase = '';
            $this->mailer->DKIM_identity = $this->username;

            $this->mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        }

        // Funzione per la disabilitazione dell'output dalla libreria
        public function disableDebug() {
            $this->mailer->SMTPDebug = 0;
        }

        // Funzione per l'invio della mail
        public function sendMail($from, $to, $subject, $message) {

            // Assegnazione dei parametri
            $this->mailer->SetFrom($from);
            $this->mailer->Subject = $subject;
            $this->mailer->MsgHTML($message);

            $this->mailer->ClearAddresses();
            $this->mailer->AddAddress($to);

            // Gestione dell' invio in caso di errore restituisco false
            if(!$this->mailer->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
             } else {
                return false;
             }

            // Ripulisco il parametro destinatari
            $this->mailer->ClearAllRecipients( );
        }
        public function sendGenericEmail($email, $object, $body){
            $content .= file_get_contents("./../email/email.html");

            $content .= $body;
            
            $this->sendMail("noreply@fantavikings.it", $email, $object, $content);
        }

        public function sendRegistrationEmail($email, $registration, $object, $body) {

          $content .= file_get_contents("./../email/email.html");

          $content .= $body;
          
          $this->sendMail("noreply@fantavikings.it", $email, $object, $content);

        }

        public function sendRecoverPassword($email, $body) {

          $content .= file_get_contents("./../email/email.html");

          $content .= $body;

          $this->sendMail("noreply@fantavikings.it", $email, "Reset Fanta-GOT", $content);

        }

        // Funzione per allegare file alla mail da inviare
        public function addAttachment($filepath) {

            // Se il file esiste effettuo attacco l'allegato
            if (file_exists($filepath)) {

                $this->mailer->addAttachment($filepath);
                return true;
            }else{
                return false;
            }
        }

    }

?>
