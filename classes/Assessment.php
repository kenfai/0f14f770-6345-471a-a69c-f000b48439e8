<?php

/**
 * Assessment class
 *   - Represents an Assessment data
 */
class Assessment {
    /**
     * Assessment ID
     *
     * @var string
     */
    public readonly string $id;

    /**
     * Assessment name
     * 
     * @var string
     */
    public readonly string $name;

    /**
     * Assessment constructor
     * 
     * @param string $id
     * @param string $name
     * 
     * @return void
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}