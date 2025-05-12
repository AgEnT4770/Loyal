<?php
require_once 'User.php';
require_once '../db.php';

class Customer extends User {
    private $loyaltyPoints;
    private $subscription;
    private $db;

    public function __construct($user_id, $name, $email, $password, $loyaltyPoints = 0, $subscription = "None") {
        parent::__construct($user_id, $name, $email, $password, "customer");
        $this->loyaltyPoints = $loyaltyPoints;
        $this->subscription = $subscription;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getLoyaltyPoints() { return $this->loyaltyPoints; }
    public function getSubscription() { return $this->subscription; }

    public function setLoyaltyPoints($points) { 
        $this->loyaltyPoints = $points; 
        $this->updateLoyaltyPointsInDB();
    }

    public function setSubscription($subscription) { 
        $this->subscription = $subscription; 
        $this->updateSubscriptionInDB();
    }

    public function redeemRewards() {
        return "Customer {$this->name} is redeeming rewards.";
    }

    public function subscribeToOffer($offer) {
        $this->subscription = $offer;
        $this->updateSubscriptionInDB();
        return "Customer {$this->name} subscribed to {$offer}.";
    }

    private function updateLoyaltyPointsInDB() {
        $sql = "UPDATE users SET loyaltyPoints = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $this->loyaltyPoints, $this->user_id);
        $stmt->execute();
    }

    private function updateSubscriptionInDB() {
        $sql = "UPDATE users SET subscription = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $this->subscription, $this->user_id);
        $stmt->execute();
    }
}
?>
