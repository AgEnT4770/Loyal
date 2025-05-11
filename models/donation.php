<?php
class Donation {
    private $donation_id;
    private $user_id;
    private $charity_id;
    private $amount;
    private $date;

    public function getDonationId() { return $this->donation_id; }
    public function setDonationId($donation_id) { $this->donation_id = $donation_id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getCharityId() { return $this->charity_id; }
    public function setCharityId($charity_id) { $this->charity_id = $charity_id; }

    public function getAmount() { return $this->amount; }
    public function setAmount($amount) { $this->amount = $amount; }

    public function getDate() { return $this->date; }
    public function setDate($date) { $this->date = $date; }
}
?>