<?php
require_once 'User.php'; // Ensure User class is included
require_once '../db.php'; // Include Database class

class Merchant extends User {
    private $merchantName;
    private $offers = [];
    private $db;

    public function __construct($user_id, $name, $email, $password, $merchantName = null) {
        parent::__construct($user_id, $name, $email, $password, "merchant");
        $this->merchantName = $merchantName ?? $name; // Default to personal name if not set
        $this->db = Database::getInstance()->getConnection(); // Get DB connection
    }

    // Getters
    public function getMerchantName() { return $this->merchantName; }
    public function getOffers() { return $this->offers; }

    // Setters
    public function setMerchantName($merchantName) { 
        $this->merchantName = $merchantName; 
        $this->updateMerchantNameInDB();
    }

    // Merchant-specific functionality
    public function addOffer($offer) {
        $this->offers[] = $offer;
        $this->insertOfferIntoDB($offer);
        return "Merchant {$this->name} added a new offer: {$offer}.";
    }

    public function viewTransactions() {
        return "Merchant {$this->name} is viewing transactions.";
    }

    // Update merchant name in the database
    private function updateMerchantNameInDB() {
        $sql = "UPDATE users SET merchant_name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $this->merchantName, $this->user_id);
        $stmt->execute();
    }

    // Insert new offer into the database
    private function insertOfferIntoDB($offer) {
        $sql = "INSERT INTO offers (merchant_id, offer_details) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $this->user_id, $offer);
        $stmt->execute();
    }
}
?>
