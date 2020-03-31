<?php


namespace App\Controller;

use App\Entity\Choice;
use App\Entity\Question;
use App\Repository\IQuestionRepository;
use App\Service\TranslateService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// TODO: root URL does nothing!

/**
 * Class QuestionController
 * @package App\Controller
 * @Route("/")
 */
class QuestionController
{
    /**
     * @var IQuestionRepository
     */
    private IQuestionRepository $questionRepository;
    /**
     * @var TranslateService
     */
    private TranslateService $translateService;

    public function __construct(IQuestionRepository $questionRepository, TranslateService $translateService)
    {
        $this->questionRepository = $questionRepository;
        $this->translateService = $translateService;
    }

    /**
     * @param string $lang Language (ISO-639-1 code) in which the questions and choices should be translated
     * @return JsonResponse
     * @Route("questions/{lang}", name="get_questions", methods={"GET"})
     * Returns the list of translated questions and associated choices
     * @throws \ErrorException
     */
    public function getQuestions(string $lang): JsonResponse
    {
        // TODO: validate if lang has one possible value

        $questions = $this->questionRepository->GetQuestions();
        $translated = $this->translateService->Translate($questions, $lang);

        //TODO: remove quotes unicoding
        return new JsonResponse(json_encode($translated), Response::HTTP_OK);
    }

    /**
     * @Route("questions", name="add_question", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postQuestion(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $text = $data['text'];
        $createdAt = $data['createdAt'];
        $choices = $data['choices'];

        if (empty($text) || empty($createdAt)) {
            throw new InvalidArgumentException('Question text or createdAt empty!');
        }

        if (empty($choices) || !is_array($choices)) {
            throw new InvalidArgumentException('Question choices incorrect!');
        }

        $question = new Question($text, $createdAt);
        $questionChoices = [];

        foreach ($choices as $choice) {
            $choiceText = $choice['text'];

            if (empty($choiceText)) {
                throw new InvalidArgumentException('Question choice text empty!');
            }
            array_push($questionChoices, new Choice($choiceText));
        }
        $question->choices = $questionChoices;

        $this->questionRepository->AddQuestion($question);

        return new JsonResponse(['status' => 'Question created!'], Response::HTTP_CREATED);
    }
}