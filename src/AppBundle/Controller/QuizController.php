<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Entity\Level;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;
use AppBundle\Model\AbstractQuizManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Model\QuizManagerInterface;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;


/**
 * Class QuizController
 * @package AppBundle\Controller
 *
 * @author Ange Paterson
 */
class QuizController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
public function listAction(Request $request)
{
    /** @var PaginationManager $paginationManager */
    $paginationManager = $this->get('oka_pagination.manager');

    try {
        /** @var PaginationResultSet $paginationResultSet */
        $paginationResultSet = $paginationManager->paginate('quiz', $request, [], ['createdAt' => 'ASC']);

        return new JsonResponse($this->get('jms_serializer')->toArray($paginationResultSet), 200);
    } catch (SortAttributeNotAvailableException $e) {
        return new JsonResponse([
            'message' => $e->getMessage()
        ], 400);
    }
}


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function createAction(Request $request, $id)
    {
        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'mode' => new Assert\Optional([
                new Assert\Collection([
                    'id' => new Assert\Required([ new Assert\Type(['type' => 'integer'])])
                ])
            ]),
            'level' => new Assert\Optional([
                new Assert\Collection([
                    'id' => new Assert\Required([ new Assert\Type(['type' => 'integer'])])
                ])
            ]),
            'category' => new Assert\Optional([
                new Assert\Collection([
                    'id' => new Assert\Required([ new Assert\Type(['type' => 'integer'])])
                ])
            ]),
            'user' => new Assert\Optional([
                new Assert\Collection([
                    'id'=> new Assert\Required([new Assert\Type(['type' => 'integer'])])
                ])
            ]),
            'number' => new Assert\Optional([new Assert\Type(['type' => 'integer'])])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->Validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');

            /** @var User $user */
            if (!$user = $em->getRepository('AppBundle:User')->find($id))
            {
                return new JsonResponse(['Message' => 'User not found'], 404);
            }

            if (isset($requestContent['category'])) {
                /** @var Category $category */
                if (!$category = $em->getRepository('AppBundle:Category')->find($requestContent['category']['id']))
                {
                    return new JsonResponse(['Message' => 'Category not found'], 404);
                }
            } else {
                $category = null;
            }

            if (isset($requestContent['level'])) {
                /** @var Level $level */
                if (!$level = $em->getRepository('AppBundle:Level')->find($requestContent['level']['id'])) {
                    return new JsonResponse(['Message' => 'Level not found'], 404);
                }
            } else {
                $level = null;
            }

            /** @var QuizManagerInterface $quizManager */
            $quizManager = $this->get('app.default_quiz_manager');
            $quiz = $quizManager->create($user);

            return new JsonResponse($this->get('jms_serializer')->toArray($quiz), 201);
        }

        $extras = [];
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $extras[] = [
                'property' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return new JsonResponse([
            'message' => 'Request invalid.',
            'extra' => $extras
        ], 400);
    }

    /**
     * @param $userId
     * @param $id
     * @return JsonResponse
     */
    public function readAction($userId, $id)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        ;

        /** @var User $user */
        if ($user = $em->getRepository(User::class)->find($userId))
        {
            if ($quiz = $em->getRepository(Quiz::class)->find($id))
            {
                return new JsonResponse($this->get('jms_serializer')->toArray($quiz), 200);
            }
            else{
                return new JsonResponse(['message' => sprintf('Quiz from user "%s" not found.', $user->getUsername())], 404);
            }
        }
        else{
            return new JsonResponse(['message' => 'User not found'], 404);
        }

    }




}