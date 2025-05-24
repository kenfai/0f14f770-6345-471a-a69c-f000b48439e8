<?php

class Student
{
    /**
     * Student ID
     * 
     * @var string
     */
    private string $id;

    /**
     * Student first name
     * 
     * @var string
     */
    private ?string $first_name;

    /**
     * Student last name
     * 
     * @var string
     */
    private ?string $last_name;

    /**
     * Student year level
     * 
     * @var int
     */
    private int $year_level;

    /**
     * Student constructor
     * 
     * @param string $id
     * @param string|null $first_name
     * @param string|null $last_name
     * @param int $year_level
     * @return void
     */
    public function __construct(
        string $id,
        ?string $first_name,
        ?string $last_name,
        int $year_level
    ) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->year_level = $year_level;
    }

    /**
     * Gets the student full name
     * 
     * @return string
     */
    public function getFullName(): string
    {
        return implode(' ', array_filter(
            [
                $this->first_name,
                $this->last_name,
            ])
        );
    }
}