<?php
class Reward {
    private $reward_id;
    private $title;
    private $description;
    private $points_required;

    public function getRewardId() { return $this->reward_id; }
    public function setRewardId($reward_id) { $this->reward_id = $reward_id; }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getPointsRequired() { return $this->points_required; }
    public function setPointsRequired($points_required) { $this->points_required = $points_required; }
}