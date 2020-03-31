<?php


namespace App\Repository;


use App\Entity\Choice;
use App\Entity\Question;
use InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\File\Exception\NoFileException;

// TODO: fix problems with quotes

/**
 * Class CsvQuestionRepository
 * @package App\Repository
 * The format of CSV file is: "Question text", "Created At", "Choice 1", "Choice", "Choice 3"
 * We think that there are always 3 choises for each question
 */
class CsvQuestionRepository implements IQuestionRepository
{
    //  TODO: use to take values from env/config
    private string $file = "storage_files\\questions.csv";
    private int $choiceCount = 3;

    public function AddQuestion(Question $question)
    {
        if (count($question->choices) != $this->choiceCount) {
            throw new InvalidArgumentException(sprintf("CSV format supports only %d choices, found %d", $this->choiceCount, count($question->choices)));
        }

        $func = function(Choice $choice) {
            return $choice->text;
        };
        $choiceTexts = array_map($func, $question->choices);

        // NOTE: we doesn't check questions for duplicates as there is no such requirement. So just add one more row.
        if (($handle = fopen($this->file, "a")) !== FALSE) {
            try {
                if (fputcsv($handle, [$question->text, $question->createdAt, ...$choiceTexts]) === FALSE) {
                    throw new NoFileException("Can't write to storage");
                }
            } finally {
                fclose($handle);
            }
        } else {
            throw new NoFileException("Can't access storage");
        }
    }

    public function GetQuestions()
    {
        $row = 1;
        $result = array();

        if (($handle = fopen($this->file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) { // read row

                if (count($data) !== 2 + $this->choiceCount) {
                    throw new InvalidTypeException("Wrong CSV file format. It should be 5 columns.");
                }

                if ($row !== 1) {
                    $question = new Question($data[0], $data[1]);
                    $question->choices = [new Choice($data[2]), new Choice($data[3]), new Choice($data[4])]; // TODO: should be able to work with any choice number
                    array_push($result, $question);
                }

                $row++;
            }

            fclose($handle);
        } else {
            throw new NoFileException("Can't access storage");
        }

        return $result;
    }
}