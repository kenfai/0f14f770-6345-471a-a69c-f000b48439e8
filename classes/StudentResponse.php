<?php

class StudentResponse
{
    /**
     * Response ID
     *
     * @var string
     */
    private string $id;

    /**
     * Assessment ID
     * 
     * @var string
     */
    public string $assessment_id;

    /**
     * Assigned date
     * 
     * @var DateTime
     */
    private DateTime $assigned_date;

    /**
     * Started date
     * 
     * @var DateTime
     */
    private ?DateTime $started_date;

    /**
     * Completed date
     * 
     * @var DateTime
     */
    public ?DateTime $completed_date;

    /**
     * Student
     * 
     * @var Student
     */
    public Student $student;

    /**
     * Year level
     * 
     * @var int
     */
    private int $year_level;

    /**
     * Responses
     * 
     * @var array
     */
    private array $responses;

    /**
     * Result score
     * 
     * @var int
     */
    private int $score;

    /**
     * Constructor
     * 
     * @param string $id
     * @param string $assessment_id
     * @param DateTime $assigned_date
     * @param DateTime|null $started_date
     * @param DateTime|null $completed_date
     * @param Student $student
     * @param int $year_level
     * @param array $responses
     * @param int $score
     * 
     * @return void
     */
    public function __construct(
        string $id,
        string $assessment_id,
        DateTime $assigned_date,
        ?DateTime $started_date,
        ?DateTime $completed_date,
        Student $student,
        int $year_level,
        array $responses,
        int $score,
    ) {
        $this->id = $id;
        $this->assessment_id = $assessment_id;
        $this->assigned_date = $assigned_date;
        $this->started_date = $started_date;
        $this->completed_date = $completed_date;
        $this->student = $student;
        $this->year_level = $year_level;
        $this->responses = $responses;
        $this->score = $score;
    }

    /**
     * Gets the result score
     * 
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * Gets the total number of questions
     * 
     * @return int
     */
    public function getTotalQuestions(): int
    {
        return count($this->responses);
    }

    /**
     * Gets the total number of correct answers by question strand
     * 
     * @param string $strand
     * 
     * @return int
     */
    public function getScoreByStrand(string $strand): int
    {
        $score = 0;

        $strand_responses = array_filter($this->responses, function($response) use ($strand) {
            return $response->question->strand === $strand;
        });

        $score = array_sum(array_map(function($response) {
            return $response->response === $response->question->config->key ? 1 : 0;
        }, $strand_responses));

        return $score;
    }

    /**
     * Gets the total number of questions by question strand
     * 
     * @param string $strand
     * 
     * @return int
     */
    public function getTotalQuestionsByStrand(string $strand): int
    {
        $total = 0;

        $strand_responses = array_filter($this->responses, function($response) use ($strand) {
            return $response->question->strand === $strand;
        });

        $total = count($strand_responses);

        return $total;
    }

    /**
     * Gets the wrong answers
     * 
     * @return array
     */
    public function getWrongAnswers(): array
    {
        $wrong_answers = array_filter($this->responses, function($response) {
            return $response->response !== $response->question->config->key;
        });

        return $wrong_answers;
    }
}