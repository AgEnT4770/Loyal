<?php
require_once 'User.php';
require_once '../db.php';

class Merchant extends User {
    private $merchantName;
    private $offers = [];
    private $db;

    public function __construct($user_id, $name, $email, $password, $merchantName = null) {
        parent::__construct($user_id, $name, $email, $password, "merchant");
        $this->merchantName = $merchantName ?? $name;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getMerchantName() { return $this->merchantName; }
    public function getOffers() { return $this->offers; }

    public function setMerchantName($merchantName) { 
        $this->merchantName = $merchantName; 
        $this->updateMerchantNameInDB();
    }

    public function addOffer($offer) {
        $this->offers[] = $offer;
        $this->insertOfferIntoDB($offer);
        return "Merchant {$this->name} added a new offer: {$offer}.";
    }

    public function viewTransactions() {
        return "Merchant {$this->name} is viewing transactions.";
    }

    private function updateMerchantNameInDB() {
        $sql = "UPDATE users SET merchant_name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $this->merchantName, $this->user_id);
        $stmt->execute();
    }

    private function insertOfferIntoDB($offer) {
        $sql = "INSERT INTO offers (merchant_id, offer_details) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $this->user_id, $offer);
        $stmt->execute();
    }
}
?>
