<?php

namespace App\Services;

use App\Constants\DecisionTableConstants;
use App\Traits\CountriesTrait;

class FlightClaimabilityService
{
    use CountriesTrait;

    /**
     * Check is flight claimable.
     *
     * @param array $flightData
     *   Flight data.
     *
     * @return bool
     *   TRUE if claimable.
     */
    public function isFlightClaimable(array $flightData): bool
    {
        if (empty($flightData)) {
            return false;
        }

        foreach (DecisionTableConstants::RULES as $ruleConditions) {
            if (!$this->passConditions($ruleConditions, $flightData)) {
                continue;
            }

            return $ruleConditions[DecisionTableConstants::CLAIMABLE_KEY];
        }

        return false;
    }

    /**
     * Check if flight passes rule conditions.
     *
     * @param array $ruleConditions
     *   Rule condition.
     * @param array $flightData
     *   Flight data.
     *
     * @return bool
     *   TRUE if passes.
     */
    private function passConditions(array $ruleConditions, array $flightData): bool
    {
        foreach ($ruleConditions as $conditionKey => $condition) {
            if ($conditionKey === DecisionTableConstants::CLAIMABLE_KEY) {
                continue;
            }

            if (!$this->passCondition($conditionKey, $condition, $flightData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if flight passes rule condition.
     *
     * @param $conditionKey
     *   Condition key.
     * @param $condition
     *   Rule Condition.
     * @param $flightData
     *   Flight data.
     *
     * @return bool
     *   TRUE if passes.
     */
    private function passCondition($conditionKey, $condition, $flightData): bool
    {
        return match ($conditionKey) {
            DecisionTableConstants::EU_CONDITION_KEY => $this->passDepartureFromEuCondition($condition, $flightData['Countries']),
            DecisionTableConstants::CANCEL_CONDITION_KEY => $this->passCancelledDaysCondition($condition, $flightData),
            DecisionTableConstants::DELAY_CONDITION_KEY => $this->passDelayedHoursCondition($condition, $flightData),
            default => false,
        };
    }

    /**
     * Pass EU condition.
     *
     * @param bool $condition
     *   Rule condition.
     * @param string $country
     *   Country shortName.
     *
     * @return bool
     *   TRUE if passes.
     */
    private function passDepartureFromEuCondition(bool $condition, string $country): bool
    {
        return $condition === $this->isEuCountry($country);
    }

    /**
     * Pass cancelled days condition.
     *
     * @param string $condition
     *   Rule condition.
     * @param array $flightData
     *   Flight data.
     *
     * @return bool
     *   TRUE if passes.
     */
    private function passCancelledDaysCondition(string $condition, array $flightData): bool
    {
        if ($flightData['Status'] !== 'Cancel') {
            return false;
        }

        $condition = $flightData['Status Details'] . $condition;

        return eval("return $condition;");
    }

    /**
     * Pass cancelled days condition.
     *
     * @param string $condition
     *   Rule condition.
     * @param array $flightData
     *   Flight data.
     *
     * @return bool
     *   TRUE if passes.
     */
    private function passDelayedHoursCondition(string $condition, array $flightData): bool
    {
        if ($flightData['Status'] !== 'Delay') {
            return false;
        }

        $condition = $flightData['Status Details'] . $condition;

        return eval("return $condition;");
    }
}
