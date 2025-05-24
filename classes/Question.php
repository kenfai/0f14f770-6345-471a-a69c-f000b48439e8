<?php

class Question
{
    /**
     * Question ID
     *
     * @var string
     */
    private string $id;

    /**
     * Question stem
     * 
     * @var string
     */
    private string $stem;

    /**
     * Question type
     * 
     * @var string
     */
    private string $type;

    /**
     * Question strand
     *
     * @var string
     */
    public string $strand;

    /**
     * Question config
     * 
     * @var stdClass
     */
    public stdClass $config;

    /**
     * Question constructor
     * 
     * @param string $id
     * @param string $stem
     * @param string $type
     * @param string $strand
     * @param stdClass $config
     * 
     * @return void
     */
    public function __construct(
        string $id,
        string $stem,
        string $type,
        string $strand,
        stdClass $config,
    ) {
        $this->id = $id;
        $this->stem = $stem;
        $this->type = $type;
        $this->strand = $strand;
        $this->config = $config;
    }
}