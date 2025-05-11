<?php
class Offer {
    private $offer_id;
    private $merchant_id;
    private $title;
    private $description;
    private $points_cost;
    private $expiry_date;

    public function getOfferId() { return $this->offer_id; }
    public function setOfferId($offer_id) { $this->offer_id = $offer_id; }

    public function getMerchantId() { return $this->merchant_id; }
    public function setMerchantId($merchant_id) { $this->merchant_id = $merchant_id; }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getPointsCost() { return $this->points_cost; }
    public function setPointsCost($points_cost) { $this->points_cost = $points_cost; }

    public function getExpiryDate() { return $this->expiry_date; }
    public function setExpiryDate($expiry_date) { $this->expiry_date = $expiry_date; }
}