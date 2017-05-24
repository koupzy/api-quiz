<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 24/05/17
 * Time: 09:20
 */

namespace AppBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Question as Question;

/**
 * Class QuestionController
 * @package AppBundle\Controller
 * @author joel
 */
class QuestionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getQuestionsAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('AppBundle:Question')->findAll();

        $formatted = [];


        foreach ($questions as $question)
        {
            $formatted[] = [
                "id" => $question->getId(),
                "content" => $question->getContent,
                "choice" => $question->hasMultipleChoice,
                "category" => $question->getCategory,
                array("propositions" => $question->getPropositions) ,
                array("scores" => $question->getScores)
            ];
        }

        return new JsonResponse($formatted);

    }

    public function getQuestionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')
                       ->find($request->get('id'));

        if (empty($question))
        {
            return new JsonResponse(["message"=>"question non trouve"],Request::HTTP_NOT_FOUND);
        }

        $formatted[] = [
            "id" => $question->getId(),
            "content" => $question->getContent,
            "choice" => $question->hasMultipleChoice,
            "category" => $question->getCategory,
            array("propositions" => $question->getPropositions) ,
            array("scores" => $question->getScores)
        ];

        return new JsonResponse($formatted);
    }

}