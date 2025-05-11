<?php
require_once 'User.php'; // Ensure User class is included
require_once '../db.php'; // Include Database class

class Customer extends User {
    private $loyaltyPoints;
    private $subscription;
    private $db;

    public function __construct($user_id, $name, $email, $password, $loyaltyPoints = 0, $subscription = "None") {
        parent::__construct($user_id, $name, $email, $password, "customer");
        $this->loyaltyPoints = $loyaltyPoints;
        $this->subscription = $subscription;
        $this->db = Database::getInstance()->getConnection(); // Get DB connection
    }

    // Getters
    public function getLoyaltyPoints() { return $this->loyaltyPoints; }
    public function getSubscription() { return $this->subscription; }

    // Setters
    public function setLoyaltyPoints($points) { 
        $this->loyaltyPoints = $points; 
        $this->updateLoyaltyPointsInDB();
    }

    public function setSubscription($subscription) { 
        $this->subscription = $subscription; 
        $this->updateSubscriptionInDB();
    }

    // Customer-specific functionality
    public function redeemRewards() {
        return "Customer {$this->name} is redeeming rewards.";
    }

    public function subscribeToOffer($offer) {
        $this->subscription = $offer;
        $this->updateSubscriptionInDB();
        return "Customer {$this->name} subscribed to {$offer}.";
    }

    // Update loyalty points in the database
    private function updateLoyaltyPointsInDB() {
        $sql = "UPDATE users SET loyaltyPoints = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $this->loyaltyPoints, $this->user_id);
        $stmt->execute();
    }

    // Update subscription in the database
    private function updateSubscriptionInDB() {
        $sql = "UPDATE users SET subscription = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $this->subscription, $this->user_id);
        $stmt->execute();
    }
}
?>
