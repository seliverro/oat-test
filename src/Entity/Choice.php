<?php


namespace App\Entity;

/**
 * Class Choice
 * @package App\Entity
 * NOTE: Business logic level entities created for some probable future need
 */
class Choice
{
    public string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }
}