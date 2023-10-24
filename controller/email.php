<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {
    private $queue;
    private $mailer;

    public function __construct($queue) {
        $this->queue = $queue;
        $this->mailer = new PHPMailer(true);
        $this->initializeMailer();
    }

    private function initializeMailer() {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = getenv('MAIL_HOST');
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = getenv('MAIL_USERNAME');
            $this->mailer->Password = getenv('MAIL_PASSWORD');
            $this->mailer->SMTPSecure = 'tls';
            $this->mailer->Port = getenv('MAIL_PORT');
            $this->mailer->setFrom(getenv('MAIL_FROM_ADDRESS') , getenv('MAIL_FROM_NAME'));
            $this->mailer->SMTPDebug = 0;
        } catch (Exception $e) {
            $error = "Mailer setup error: " . $e->getMessage();
            $this->log($error);
        }
    }
    
    public function sendEmail($data) {
        $this->queue->enqueue($data);
        $this->log("Email added to the queue");
        return ["message" => "Email added to the queue."];
    }

    public function processQueue() {
        while (!$this->queue->isEmpty()) {
            $emailData = $this->queue->dequeue();
            $to = $emailData['to'];
            $subject = $emailData['subject'];
            $message = $emailData['message'];

            try {
                $mailer = clone $this->mailer;
                $mailer->addAddress($to);
                $mailer->Subject = $subject;
                $mailer->Body = $message;
                $mailer->send();

                $this->log("Email sent to $to");
            } catch (Exception $e) {
                $error = "Email not sent to  $to: " . $e->getMessage();
                $this->log($error);
            }
        }
    }

    private function log($message) { 
        require 'config/database.php';
        $query = "INSERT INTO log (message, created_at) VALUES (:message, NOW())";
        $insert_db = $db->prepare($query);

        $insert_db->bindParam(':message', $message, PDO::PARAM_STR);

        $insert_db->execute();
    }
}
