<?php

    class MailComposer {

        private $mailer;

        // Parametri di configurazione del mailer
        private $username = "";
        private $password = "";

        private $host = "";
        private $port = 587;

        /* Ottengo i dati dal file di impostazioni
            tramite le classe che li gestisce
        */
        function __construct() {

            // Configurazione della libreria mailer
            $this->mailer = new PHPMailer;
            $this->mailer->isSMTP();
            $this->mailer->SMTPDebug = 1; // TODO rimuovere al release
            $this->mailer->Debugoutput = 'html';
            $this->mailer->SMTPSecure = "tls";
            $this->mailer->SMTPAuth = true;

            $this->mailer->Username = $this->username;
            $this->mailer->Password = $this->password;
            $this->mailer->Host = $this->host;
            $this->mailer->Port = $this->port;

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
            $this->mailer->ClearAllRecipients();
        }

        public function sendRegistrationEmail($email, $registration, $object, $body) {

          $content .= file_get_contents("./../email/email.html");

          $content .= $body;

          $this->sendMail("", $email, $object, $content);

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
