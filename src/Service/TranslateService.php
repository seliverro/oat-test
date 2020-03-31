<?php


namespace App\Service;


use App\Entity\Choice;
use App\Entity\Question;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateService
{
    private GoogleTranslate $googleTranslate;

    public function __construct()
    {
        // Detect language automatically. We guess that sources are in English but can't be sure.
        $this->googleTranslate = new GoogleTranslate();
        $this->googleTranslate->setOptions(['verify' => false])->setSource();
    }

    public function Translate(array $questions, string $lang)
    {
        $translateFunc = function (Question $question) {
            return $this->TranslateQuestion($question, $this->googleTranslate);
        };

        $this->googleTranslate->setTarget($lang);

        return array_map($translateFunc, $questions);
    }

    private function TranslateQuestion(Question $question, GoogleTranslate $translate)
    {
        try {
            $result = new Question($translate->translate($question->text), $question->createdAt);


            $translateFunc = function (Choice $choice) use ($translate) {
                return new Choice($translate->translate($choice->text));
            };

            $result->choices = array_map($translateFunc, $question->choices);

            return $result;

        } catch (\ErrorException $e) {
            //TODO: process it correctly
        }
    }
}