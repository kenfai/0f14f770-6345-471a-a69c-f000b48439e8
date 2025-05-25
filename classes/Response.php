<?php

class Response
{
    /**
     * Question ID
     * 
     * @var string
     */
    private readonly string $question_id;

    /**
     * Question
     * 
     * @var Question
     */
    public readonly Question $question;

    /**
     * Response answer
     * 
     * @var string
     */
    public readonly string $response;

    /**
     * Response constructor
     * 
     * @param string $question_id
     * @param Question $question
     * @param string $response
     * 
     * @return void
     */
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