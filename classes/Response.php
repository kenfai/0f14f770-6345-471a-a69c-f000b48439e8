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
     * Question
     * 
     * @var Question
     */
    public Question $question;

    /**
     * Response answer
     * 
     * @var string
     */
    public string $response;

    public function __construct(
        string $question_id,
        Question $question,
        string $response
    ) {
        $this->question_id = $question_id;
        $this->question = $question;
        $this->response = $response;
    }
}