<?php

require_once(__DIR__ . '/Select.php');
require_once(__DIR__ . '/../../Modele/Participation/Performance.php');

class SelectPerformance extends Select {

    public function __construct(
            ?string $description,
            ?string $selectedValue = null
    ) {
        $values = [];
        foreach (Performance::cases() as $performance) {
            $values[$performance->name] = $performance->name;
        }

        parent::__construct($values, "performance", $description, $selectedValue);
    }
}