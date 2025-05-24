<?php

class Option
{
    /**
     * Option ID
     *
     * @var string
     */
    private string $id;

    /**
     * Option label
     * 
     * @var string
     */
    public string $label;

    /**
     * Option value
     * 
     * @var string
     */
    public string $value;
    
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