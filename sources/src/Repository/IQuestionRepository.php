<?php


namespace App\Repository;


use App\Entity\Question;

interface IQuestionRepository
{
    public function addQuestion(Question $question);
    public function getQuestions();
}