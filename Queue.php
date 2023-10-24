<?php
class Queue {
    private $queue = [];

    public function enqueue($emailData) {
        $this->queue[] = $emailData;
    }

    public function dequeue() {
        if (count($this->queue) > 0) {
            return array_shift($this->queue);
        }
        return null;
    }

    public function isEmpty() {
        return empty($this->queue);
    }
}