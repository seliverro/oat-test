<?php


namespace App\Repository;


use App\Entity\Question;

interface IQuestionRepository
{
    public function AddQuestion(Question $question);
    public function GetQuestions();
}