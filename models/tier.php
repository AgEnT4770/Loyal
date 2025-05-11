<?php
class Tier {
    private $tier_id;
    private $name;
    private $min_points;
    private $benefits;

    public function getTierId() { return $this->tier_id; }
    public function setTierId($tier_id) { $this->tier_id = $tier_id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getMinPoints() { return $this->min_points; }
    public function setMinPoints($min_points) { $this->min_points = $min_points; }

    public function getBenefits() { return $this->benefits; }
    public function setBenefits($benefits) { $this->benefits = $benefits; }
}
