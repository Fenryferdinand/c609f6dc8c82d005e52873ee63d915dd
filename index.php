<?php
require_once 'controller/email.php';
require 'config/database.php';
require_once '../src/Queue.php';

$queue = new Queue();
$emailService = new Email($queue);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'send-email') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $emailService->sendEmail($data);
    echo json_encode($result);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'process-queue') {
    $emailService->processQueue();
    echo json_encode(["message" => "Emails Will be sent"]);
}
