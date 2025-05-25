<?php

/**
 * Reporting class
 *   - Provides methods to generate reports
 */
final class Reporting
{
    /**
     * Array of students
     * 
     * @var array
     */
    private readonly array $students;

    /**
     * Array of assessments
     * 
     * @var array
     */
    private readonly array $assessments;

    /**
     * Array of questions
     * 
     * @var array
     */
    private readonly array $questions;

    /**
     * Array of responses
     * 
     * @var array
     */
    private readonly array $responses;

    /**
     * Reporting constructor
     * 
     * @return void
     */
    public function __construct()
    {
        // Load the data from the JSON files into memory
        try {
            $this->students = $this->loadData('students');
            $this->assessments = $this->loadData('assessments');
            $this->questions = $this->loadData('questions');
            $this->responses = $this->loadData('student-responses');
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            exit;
        }
    }

    /**
     * Main function of the program entry point
     * 
     * @return void
     */
    public function main(): void
    {
        $this->output();
    }

    /**
     * Outputs the prompt and report to the console
     * 
     * @return void
     * 
     * @throws Exception
     */
    private function output(): void
    {
        echo "Please enter the following\n";

        $student_id = readline("Student ID: ");

        try {
            $this->getStudent($student_id);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            exit;
        }

        $report_type = readline("Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ");

        try {
            $this->generateReport($student_id, $report_type);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            exit;
        } catch (TypeError $e) {
            echo "ERROR: Invalid report type!\n";
            exit;
        }
    }

    /**
     * Loads the data from the specified file
     * 
     * @param string $file
     * 
     * @return mixed
     * 
     * @throws Exception
     * @throws JsonException
     */
    private function loadData(string $file)
    {
        $path = 'data/' . $file . '.json';

        if (! file_exists($path)) {
            throw new Exception("ERROR: Could not find $file.json!");
        }

        $json_raw = file_get_contents($path);

        try {
            $data = json_decode(
                json: $json_raw,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new Exception("ERROR: Could not load data from $file.json");
        }

        return $data;
    }

    /**
     * Generates a report based on the report type
     * 
     * @param string $student_id
     * @param int $report_type
     * 
     * @return void
     */
    private function generateReport(string $student_id, int $report_type): void
    {
        $report = [];

        switch ($report_type) {
            case 1:
                $report = $this->generateDiagnosticReport($student_id);
                break;
            case 2:
                $report = $this->generateProgressReport($student_id);
                break;
            case 3:
                $report = $this->generateFeedbackReport($student_id);
                break;
            default:
                throw new Exception("ERROR: Invalid report type!");
        }

        echo "\n";
        echo implode("\n", $report);
        echo "\n";
    }

    /**
     * Generates a diagnostic report for a student
     * 
     * @param string $student_id
     * 
     * @return array
     */
    private function generateDiagnosticReport(string $student_id): array
    {
        $student_responses = $this->getStudentResponses($student_id);
        $student_recent_response = $this->getRecentCompletedResponse($student_responses);

        $assessment = $this->getAssessment($student_recent_response->assessment_id);
        $student_name = $student_recent_response->student->getFullName();
        $completed_date = $student_recent_response->completed_date->format('jS F Y h:i A');

        $score = $student_recent_response->getScore();
        $total_questions = $student_recent_response->getTotalQuestions();

        $completed = "$student_name recently completed $assessment->name assessment on $completed_date";
        $result = "He got $score questions right out of $total_questions. Details by strand given below:";

        $strands = $this->getQuestionStrands();

        $score_breakdown = [];

        foreach ($strands as $strand) {
            $score = $student_recent_response->getScoreByStrand($strand);
            $total_questions = $student_recent_response->getTotalQuestionsByStrand($strand);
            $score_breakdown[] = str_replace(
                search: 'Number',
                replace: 'Numeracy',
                subject: "$strand: $score out of $total_questions correct"
            );
        }

        return array_merge([
                $completed,
                $result,
                "",
            ],
            $score_breakdown,
            [""],
        );
    }

    /**
     * Generates a progress report for a student
     * 
     * @param string $student_id
     * 
     * @return array
     */
    private function generateProgressReport(string $student_id): array
    {
        $report = [];

        $student_responses = $this->getStudentResponses($student_id);

        $student = $this->getStudent($student_id);
        $student_name = $student->getFullName();

        foreach ($this->assessments as $assessment) {
            $assessment = $this->getAssessment($assessment->id);

            $student_responses_by_assessment = array_filter($student_responses, function($response) use ($assessment) {
                return $response->assessment_id === $assessment->id;
            });

            $total_student_responses = count($student_responses_by_assessment);

            $completed = "$student_name has completed $assessment->name assessment $total_student_responses times in total. Date and raw score given below:";

            $report[] = $completed;
            $report[] = "";

            // Sort the responses by earliest completed date to latest
            usort($student_responses_by_assessment, function($a, $b) {
                if ($a->completed_date === $b->completed_date) {
                    return 0;
                }

                return ($a->completed_date > $b->completed_date) ? 1 : -1;
            });

            foreach ($student_responses_by_assessment as $student_response) {
                $completed_date = $student_response->completed_date->format('jS F Y h:i A');
                $score = $student_response->getScore();
                $total_questions = $student_response->getTotalQuestions();

                $report[] = "Date: $completed_date, Raw Score: $score out of $total_questions";
            }

            $report[] = "";

            $student_recent_response = $this->getRecentCompletedResponse($student_responses);
            $student_oldest_response = $this->getOldestCompletedResponse($student_responses);

            $score_difference = $student_recent_response->getScore() - $student_oldest_response->getScore();

            if ($score_difference >= 0) {
                $score = abs($score_difference) . " more";
            } elseif ($score_difference < 0) {
                $score = abs($score_difference) . " less";
            }

            $progress = "$student_name got $score correct in the recent completed assessment than the oldest";

            $report[] = $progress;
            $report[] = "";
        }

        return $report;
    }

    /**
     * Generates a feedback report for a student
     * 
     * @param string $student_id
     * 
     * @return array
     * 
     * @throws Exception
     */
    private function generateFeedbackReport(string $student_id): array
    {
        $student_responses = $this->getStudentResponses($student_id);
        $student_recent_response = $this->getRecentCompletedResponse($student_responses);
        
        $assessment = $this->getAssessment($student_recent_response->assessment_id);
        $student_name = $student_recent_response->student->getFullName();
        $completed_date = $student_recent_response->completed_date->format('jS F Y h:i A');

        $score = $student_recent_response->getScore();
        $total_questions = $student_recent_response->getTotalQuestions();

        $completed = "$student_name recently completed $assessment->name assessment on $completed_date";
        $result = "He got $score questions right out of $total_questions. Feedback for wrong answers given below";

        $feedback_breakdown = [];

        $wrong_answers = $student_recent_response->getWrongAnswers();

        foreach ($wrong_answers as $wrong_answer) {
            $question = $wrong_answer->question->stem;
            $student_answer = $wrong_answer->question->getOption($wrong_answer->response);
            $right_answer = $wrong_answer->question->getOption($wrong_answer->question->config->key);
            $hint = $wrong_answer->question->config->hint;

            $feedback_breakdown[] = "Question: $question";
            $feedback_breakdown[] = "Your answer: $student_answer->label with value $student_answer->value";
            $feedback_breakdown[] = "Right answer: $right_answer->label with value $right_answer->value";
            $feedback_breakdown[] = "Hint: $hint";
            $feedback_breakdown[] = "";
        }

        return array_merge([
                $completed,
                $result,
                "",
            ],
            $feedback_breakdown,
        );
    }

    /**
     * Gets the student data from the students array
     * 
     * @param string $student_id
     * @return Student
     * @throws Exception
     */
    private function getStudent(string $student_id): Student
    {
        $student_data = array_filter($this->students, function($student) use ($student_id) {
            return $student->id == $student_id;
        });

        if (count($student_data) === 0) {
            throw new Exception("ERROR: Student not found!");
        }

        $student_data = array_shift($student_data);

        return new Student(
            id: $student_data->id,
            first_name: $student_data->firstName,
            last_name: $student_data->lastName,
            year_level: $student_data->yearLevel,
        );
    }

    /**
     * Gets the completed responses for a student from the responses array
     * 
     * @param string $student_id
     * @return array
     * @throws Exception
     */
    private function getStudentResponses(string $student_id): array
    {
        $result = [];

        $student_responses = array_filter($this->responses, function($response) use ($student_id) {
            return $response->student->id == $student_id
                && ! is_null($response->completed ?? null);
        });

        if (count($student_responses) === 0) {
            throw new Exception("ERROR: No completed responses found for student!");
        }

        foreach ($student_responses as $student_response) {
            $responses = array_map(function($response) {
                $question = $this->getQuestion($response->questionId);

                return new Response(
                    question_id: $response->questionId,
                    question: $question,
                    response: $response->response,
                );
            }, $student_response->responses);

            $result[] = new StudentResponse(
                id: $student_response->id,
                assessment_id: $student_response->assessmentId,
                assigned_date: DateTime::createFromFormat('d/m/Y G:i:s', $student_response->assigned),
                started_date: DateTime::createFromFormat('d/m/Y G:i:s', $student_response->started) ?? null,
                completed_date: DateTime::createFromFormat('d/m/Y G:i:s', $student_response->completed) ?? null,
                student: $this->getStudent($student_response->student->id),
                year_level: $student_response->student->yearLevel,
                responses: $responses,
                score: $student_response->results->rawScore,
            );
        }

        return $result;
    }

    /**
     * Gets the assessment data from the assessments array
     * 
     * @param string $assessment_id
     * @return Assessment
     * @throws Exception
     */
    private function getAssessment(string $assessment_id): Assessment
    {
        $assessment_data = array_filter($this->assessments, function($assessment) use ($assessment_id) {
            return $assessment->id == $assessment_id;
        });

        if (count($assessment_data) === 0) {
            throw new Exception("ERROR: Assessment not found!");
        }

        $assessment_data = array_shift($assessment_data);

        return new Assessment(
            id: $assessment_data->id,
            name: $assessment_data->name,
        );
    }

    /**
     * Gets the recent completed response for a student
     * 
     * @param array $student_responses
     * @return StudentResponse
     * @throws Exception
     * 
     */
    public static function getRecentCompletedResponse(array $student_responses): StudentResponse
    {
        $completed_response = array_reduce($student_responses, function($carry, $item) {
            if (! $carry) {
                return $item;
            }

            return $item->completed_date > $carry->completed_date ? $item : $carry;
        });

        return $completed_response;
    }

    /**
     * Gets the oldest completed response for a student
     * 
     * @param array $student_responses
     * @return StudentResponse
     * @throws Exception
     * 
     */
    public static function getOldestCompletedResponse(array $student_responses): StudentResponse
    {
        $completed_response = array_reduce($student_responses, function($carry, $item) {
            if (! $carry) {
                return $item;
            }

            return $item->completed_date < $carry->completed_date ? $item : $carry;
        });

        return $completed_response;
    }

    /**
     * Gets the question data by id
     * 
     * @param string $question_id
     * 
     * @return Question
     * 
     * @throws Exception
     */
    private function getQuestion(string $question_id): Question
    {
        $question_data = array_filter($this->questions, function($question) use ($question_id) {
            return $question->id == $question_id;
        });

        if (count($question_data) === 0) {
            throw new Exception("ERROR: Question not found!");
        }

        $question_data = array_shift($question_data);

        return new Question(
            id: $question_data->id,
            stem: $question_data->stem,
            type: $question_data->type,
            strand: $question_data->strand,
            config: $question_data->config,
        );
    }

    /**
     * Gets all unique question strands
     * 
     * @return array
     */
    private function getQuestionStrands(): array
    {
        $question_strands = array_map(function($question) {
            return $question->strand;
        }, $this->questions);

        return array_unique($question_strands);
    }
}