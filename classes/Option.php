<?php

/**
 * Option class
 *   - Represents a multiple choice Option data for a question
 */
class Option
{
    /**
     * Option ID
     *
     * @var string
     */
    private readonly string $id;

    /**
     * Option label
     * 
     * @var string
     */
    public readonly string $label;

    /**
     * Option value
     * 
     * @var string
     */
    public readonly string $value;
    
    /**
     * Option constructor
     * 
     * @param string $id
     * @param string $label
     * @param string $value
     * 
     * @return void
     */
    public function __construct(
        string $id,
        string $label,
        string $value
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
    }
}