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
    public string $stem;

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

    /**
     * Get option
     * 
     * @param string $option_id
     * 
     * @return Option
     * 
     * @throws Exception
     */
    public function getOption(string $option_id): Option
    {
        $option = array_filter($this->config->options, function($option) use ($option_id) {
            return $option->id === $option_id;
        });

        if (count($option) === 0) {
            throw new Exception('Option not found');
        }

        $option = array_shift($option);

        return new Option(
            id: $option->id,
            label: $option->label,
            value: $option->value,
        );
    }
}