<?php
class Charity {
    private $charity_id;
    private $name;
    private $description;

    public function getCharityId() { return $this->charity_id; }
    public function setCharityId($charity_id) { $this->charity_id = $charity_id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }
}
?>