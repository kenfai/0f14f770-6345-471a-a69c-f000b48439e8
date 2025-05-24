<?php

class Response
{
    /**
     * Question ID
     * 
     * @var string
     */
    private string $question_id;

    /**
     * Response answer
     * 
     * @var string
     */
    private string $response;

    public function __construct(
        string $question_id,
        string $response
    ) {
        $this->question_id = $question_id;
        $this->response = $response;
    }
}