<?php


namespace App\Entity;

/**
 * Class Question
 * @package App\Entity
 * NOTE: Business logic level entities created for some probable future need
 */
class Question
{
    public string $text;
    public string $createdAt; // format: date-time // Creation date of the question
    public array $choices; // Choices associated to the question

    public function __construct(string $text, string $createdAt)
    {
        // TODO: check createdAt for format?

        $this->text = $text;
        $this->createdAt = $createdAt;
        $this->choices = [];
    }
}