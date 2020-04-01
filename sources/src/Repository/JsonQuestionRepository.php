<?php


namespace App\Repository;


use App\Entity\Question;

class JsonQuestionRepository implements IQuestionRepository
{
    private string $file = "storage_files\\questions.json";

    public function addQuestion(Question $question)
    {
        // TODO: Implement AddQuestion() method.
    }

    public function getQuestions()
    {
        // TODO: not finished yet, there is an idea to try to use PSR-7 json validation here as well as in controllers

//        $data = json_decode(file_get_contents($this->file));
//
//        $result = array();
//
//        foreach($data as $dataItem) {
//            $text = $dataItem['text'];
//            $createdAt = $dataItem['createdAt'];
//            $choices = $dataItem['choices'];
//
//            $question = new Question($dataItem['text'], $dataItem['createdAt']);
//
//        }
    }
}