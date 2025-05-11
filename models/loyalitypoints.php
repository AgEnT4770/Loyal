<?php
class LoyaltyPoints {
    private $points_id;
    private $user_id;
    private $points;
    private $last_updated;

    public function getPointsId() { return $this->points_id; }
    public function setPointsId($points_id) { $this->points_id = $points_id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getPoints() { return $this->points; }
    public function setPoints($points) { $this->points = $points; }

    public function getLastUpdated() { return $this->last_updated; }
    public function setLastUpdated($last_updated) { $this->last_updated = $last_updated; }
}