<?php

use App\Models\Unit;
use App\Models\UnitValue;



if (!function_exists('getSmallUnit')) {
    function getSmallUnit($unitId, $smallUnit, $visited = [])
    {
        // Avoid circular dependencies
        if (in_array($unitId, $visited)) {
            return null;  // Return null to break the cycle
        }

        // Add current unit to the visited list
        $visited[] = $unitId;

        // Get the unit by its ID
        $unit = UnitValue::where('unit_id', $unitId)->first();

        if (!$unit) {
            return null;
        }

        // If the unit is the small unit, return its small_unit_value
        if ($unit->unit_id == $smallUnit) {
            return $unit->value;
        }

        // Recursively calculate the parent's value
        $parentValue = null;
        if ($unit->parent_id) {
            $parentValue = getSmallUnit($unit->parent_id, $smallUnit, $visited);
        }

        if ($parentValue !== null) {
            return $unit->value * $parentValue;
        }

        return null;
    }
}
