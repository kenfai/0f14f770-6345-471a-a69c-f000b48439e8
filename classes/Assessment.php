<?php

class Assessment {
    /**
     * Assessment ID
     *
     * @var string
     */
    public string $id;

    /**
     * Assessment name
     * 
     * @var string
     */
    public string $name;

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