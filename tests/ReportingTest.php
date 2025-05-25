<?php

use PHPUnit\Framework\TestCase;

final class ReportingTest extends TestCase
{
    /**
     * Array of mock student responses
     * 
     * @var array
     */
    private array $student_responses;

    /**
     * Mock student response
     * 
     * @var StudentResponse
     */
    private StudentResponse $student_response;

    /**
     * Setup the test environment with mock data
     * 
     * @return void
     */
    protected function setUp(): void
    {
        $this->student_responses = [
            new StudentResponse(
                id: 'mockStudentResponse1',
                assessment_id: 'mockAssessment1',
                assigned_date: new DateTime('2022-01-01 10:00:00'),
                started_date: new DateTime('2022-01-01 11:00:00'),
                completed_date: new DateTime('2022-01-01 12:00:00'),
                student: new Student(
                    id: 'mockStudent1',
                    first_name: 'Andy',
                    last_name: 'Wan',
                    year_level: 3,
                ),
                year_level: 1,
                responses: [],
                score: 0,
            ),
            new StudentResponse(
                id: 'mockStudentResponse2',
                assessment_id: 'mockAssessment1',
                assigned_date: new DateTime('2023-01-01 10:00:00'),
                started_date: new DateTime('2023-01-01 11:00:00'),
                completed_date: new DateTime('2023-01-01 12:00:00'),
                student: new Student(
                    id: 'mockStudent1',
                    first_name: 'Andy',
                    last_name: 'Wan',
                    year_level: 3,
                ),
                year_level: 2,
                responses: [],
                score: 0,
            ),
            new StudentResponse(
                id: 'mockStudentResponse3',
                assessment_id: 'mockAssessment1',
                assigned_date: new DateTime('2023-06-01 10:00:00'),
                started_date: new DateTime('2023-06-01 11:00:00'),
                completed_date: new DateTime('2023-06-01 12:00:00'),
                student: new Student(
                    id: 'mockStudent1',
                    first_name: 'Andy',
                    last_name: 'Wan',
                    year_level: 3,
                ),
                year_level: 3,
                responses: [],
                score: 0,
            ),
        ];

        $this->student_response = new StudentResponse(
            id: 'mockStudentResponse1',
            assessment_id: 'mockAssessment1',
            assigned_date: new DateTime('2022-01-01 10:00:00'),
            started_date: new DateTime('2022-01-01 11:00:00'),
            completed_date: new DateTime('2022-01-01 12:00:00'),
            student: new Student(
                id: 'mockStudent1',
                first_name: 'Andy',
                last_name: 'Wan',
                year_level: 3,
            ),
            year_level: 1,
            responses: [
                new Response(
                    question_id: 'mockQuestion1',
                    question: new Question(
                        id: 'mockQuestion1',
                        stem: 'What is 1 + 1?',
                        type: 'multiple-choice',
                        strand: 'mockStrand1',
                        config: (object) [
                            'key' => 'option1',
                        ],
                    ),
                    response: 'option1',
                ),
                new Response(
                    question_id: 'mockQuestion2',
                    question: new Question(
                        id: 'mockQuestion2',
                        stem: 'What is 2 + 2?',
                        type: 'multiple-choice',
                        strand: 'mockStrand1',
                        config: (object) [
                            'key' => 'option2',
                        ],
                    ),
                    response: 'option2',
                ),
                new Response(
                    question_id: 'mockQuestion3',
                    question: new Question(
                        id: 'mockQuestion3',
                        stem: 'What is 3 + 3?',
                        type: 'multiple-choice',
                        strand: 'mockStrand1',
                        config: (object) [
                            'key' => 'option3',
                        ],
                    ),
                    response: 'option4',
                ),
                new Response(
                    question_id: 'mockQuestion4',
                    question: new Question(
                        id: 'mockQuestion4',
                        stem: 'What is 4 + 4?',
                        type: 'multiple-choice',
                        strand: 'mockStrand2',
                        config: (object) [
                            'key' => 'option4',
                        ],
                    ),
                    response: 'option4',
                ),
                new Response(
                    question_id: 'mockQuestion5',
                    question: new Question(
                        id: 'mockQuestion5',
                        stem: 'What is 5 + 5?',
                        type: 'multiple-choice',
                        strand: 'mockStrand2',
                        config: (object) [
                            'key' => 'option1',
                        ],
                    ),
                    response: 'option4',
                ),
            ],
            score: 0,
        );
    }

    /**
     * Test getRecentCompletedResponse()
     * 
     * @return void
     */
    public function testCanGetRecentCompletedResponse(): void
    {
        $oldest_completed_response = Reporting::getRecentCompletedResponse($this->student_responses);

        $this->assertEquals($oldest_completed_response->completed_date, new DateTime('2023-06-01 12:00:00'));
    }

    /**
     * Test getOldestCompletedResponse()
     * 
     * @return void
     */
    public function testCanGetOldestCompletedResponse(): void
    {
        $oldest_completed_response = Reporting::getOldestCompletedResponse($this->student_responses);

        $this->assertEquals($oldest_completed_response->completed_date, new DateTime('2022-01-01 12:00:00'));
    }

    /**
     * Test getScoreByStrand()
     * 
     * @return void
     */
    public function testCanGetScoreByStrand(): void
    {
        $score = $this->student_response->getScoreByStrand('mockStrand1');

        $this->assertEquals($score, 2);
    }

    /**
     * Test getTotalQuestionsByStrand()
     * 
     * @return void
     */
    public function testCanGetTotalQuestionsByStrand(): void
    {
        $score = $this->student_response->getTotalQuestionsByStrand('mockStrand1');

        $this->assertEquals($score, 3);
    }

    /**
     * Test getWrongAnswers()
     * 
     * @return void
     */
    public function testCanGetWrongAnswers(): void
    {
        $wrong_answers = $this->student_response->getWrongAnswers();

        $this->assertEquals(count($wrong_answers), 2);
        foreach ($wrong_answers as $wrong_answer) {
            $this->assertTrue(in_array($wrong_answer->question_id, ['mockQuestion3', 'mockQuestion5']));
        }
    }
}