<?php
namespace App;

trait HasDebugMode
{
    /**
     * this trait allows the classes that uses it
     * to have a debug mode (i.e record any steps
     * and save the changes that occurred on
     * a specific propriety)
     */

    protected $debugMode = [];

    public function debugMode($flag)
    {
        $this->debugMode = $flag;

        return $this;
    }

    /**
     * we record the steps as an array
     * this class doesn't need to care about
     * how the steps will be "printed"
     */
    private function recordStep($message, $oldValue, $newValue)
    {
        if ($newValue != $oldValue) {
            $this->steps[] = "{$message} : value changed from: {$oldValue} to: {$newValue}";
        }
    }

    public function getDebugStack()
    {
        return $this->steps;
    }
}
