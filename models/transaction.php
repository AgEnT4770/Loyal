<?php
class Transaction {
    private $transaction_id;
    private $user_id;
    private $amount;
    private $date;
    private $type;

    public function getTransactionId() { return $this->transaction_id; }
    public function setTransactionId($transaction_id) { $this->transaction_id = $transaction_id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getAmount() { return $this->amount; }
    public function setAmount($amount) { $this->amount = $amount; }

    public function getDate() { return $this->date; }
    public function setDate($date) { $this->date = $date; }

    public function getType() { return $this->type; }
    public function setType($type) { $this->type = $type; }
}
?>