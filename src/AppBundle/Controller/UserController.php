<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @author Ange Paterson
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        /** @var PaginationManager $paginationManager */
        $paginationManager = $this->get('oka_pagination.manager');

        try
        {
            /** @var PaginationResultSet $paginationResultSet */
            $paginationResultSet = $paginationManager->paginate('user', $request, [], ['lastName' => 'ASC']);
            return new JsonResponse($this->get('jms_serializer')->toArray($paginationResultSet), 200);
        }
        catch (SortAttributeNotAvailableException $e)
        {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $validator = $this->get('validator');

        $requestContent = json_decode($request->getContent(), true);

        $constraints= new Assert\Collection([
            'lastName' => new Assert\Required([new Assert\NotNull(), new Assert\NotBlank()]),
            'firstName' => new Assert\Required([new Assert\NotNull(), new Assert\NotBlank()]),
            'userName' => new Assert\Required([new Assert\NotNull(), new Assert\NotBlank()]),
            'password' => new Assert\Required([
                new Assert\NotNull(),
                new Assert\NotBlank()
            ]),
            'quizs' => new Assert\Optional([
                new Assert\Type(['type' => 'array']),
                new Assert\All([
                    new Assert\Collection([
                    'createdAt' => new Assert\Required([new Assert\DateTime(), new Assert\NotBlank()]),
                    'updatedAt' => new Assert\Optional([new Assert\DateTime()]),
                    'paused' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
                    'finished' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
                    'note' => new Assert\Required([new Assert\Type(['type' => 'integer']), new Assert\NotBlank(), new Assert\NotNull()])
                ])])
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            $user = new User();

            $user->setFirstName($requestContent['firstName']);
            $user->setLastName($requestContent['lastName']);
            $user->setUsername($requestContent['userName']);
            $user->setPassword($requestContent['password']);


            if (isset($requestContent['quizs']))
            {
                foreach ($requestContent['quizs'] as $key => $item)
                {
                    $quiz = new Quiz();

                    $quiz->setCreatedAt($item['createdAt']);
                    $quiz->setNote($item['note']);

                    if (isset($item['updatedAt']))
                    {
                        $quiz->setUpdatedAt($item['updatedAt']);
                    }

                    if (isset($item['paused']))
                    {
                        $quiz->setPaused($item['paused']);
                    }

                    if (isset($item['finished']))
                    {
                        $quiz->setFinished($item['finished']);
                    }
                }
            }

            $errors = $validator->validate($user);

            if ($errors->count() === 0) {
                $em->persist($user);
                $em->flush();

                return new JsonResponse($this->get('jms_serializer')->toArray($user), 201);
            }
            else
            {
                return new JsonResponse(['message'=>'request not valid',
                    'property'=>$errors->get(0)->getPropertyPath(),
                    'error' => $errors->get(0)->getMessage()],
                    400);
            }
        }
        else {
            return new JsonResponse(["message" =>"request not valid", 'property' => $errors->get(0)->getPropertyPath(),  'error' => $errors->get(0)->getMessage()],400);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function readAction($id)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($id);

        if ($user === null)
        {
            return new JsonResponse(['Message' => 'User not found'], 404);
        }

        return new JsonResponse($this->get('jms_serializer')->toArray($user),200);

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id)
    {
        $requestContent = json_decode($request->getContent(), true);


        $validator = $this->get('validator');

        $constraints = new Assert\Collection([
            'lastName' => new Assert\Optional([new Assert\NotNull(), new Assert\NotBlank()]),
            'firstName' => new Assert\Optional([new Assert\NotNull(), new Assert\NotBlank()]),
            'userName' => new Assert\Optional([new Assert\NotNull(), new Assert\NotBlank()]),
            'password' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank()
            ]),
            'quizs' => new Assert\Optional([
                new Assert\Type(['type' => 'array']),
                new Assert\All([ new Assert\Collection([
                    'id' => new Assert\Optional(new Assert\Type(['type' => 'integer'])),
                    'createdAt' => new Assert\Required([new Assert\DateTime(), new Assert\NotBlank()]),
                    'updatedAt' => new Assert\Optional(new Assert\DateTime()),
                    'paused' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
                    'finished' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
                    'note' => new Assert\Required([new Assert\Type(['type' => 'integer']), new Assert\NotBlank(), new Assert\NotNull()])
                ])
                ])
            ])

        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {

            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            /** @var User $user */
            if (!$user = $em->getRepository(User::class)->find($id))
            {
                return new JsonResponse(['message' => 'User not found.'], 404);
            }


            if (isset($requestContent['lastName']))
            {
                $user->setLastName($requestContent['lastName']);
            }

            if (isset($requestContent['firstName']))
            {
                $user->setLastName($requestContent['firstName']);
            }

            if (isset($requestContent['userName']))
            {
                $user->setUsername($requestContent['userName']);
            }
            if (isset($requestContent['password']))
            {
                $user->setPassword($requestContent['password']);
            }

            if (isset($requestContent['quizs']))
            {
                $user->setQuizs(new ArrayCollection());

                foreach ($requestContent as $item) {
                    if (isset($item['id']))
                    {
                        if (!$quiz = $em->getRepository(Quiz::class)->find($item['id']))
                        {
                            return new JsonResponse([
                                'message' => sprintf('Quiz with id "%s" not found.', $item['id'])
                            ], 404);
                        }
                        else{
                            $quiz = new Quiz();
                            $em->persist($quiz);
                        }
                    }

                    if (isset($item['createdAt']))
                    {
                        $quiz->setCreatedAt($item['createdAt']);
                    }

                    if (isset($item['updatedAt']))
                    {
                        $quiz->setUpdatedAt($item['updatedAt']);
                    }

                    if (isset($item['finished']))
                    {
                        $quiz->setFinished($item['finished']);
                    }

                    if (isset($item['paused']))
                    {
                        $quiz->setPaused($item['paused']);
                    }

                    if (isset($item['note']))
                    {
                        $quiz->setNote($item['note']);
                    }
                    $user->addQuizs($quiz);
                }
            }
            $em->flush();

            return new JsonResponse($this->get('jms_serializer')->toArray($user),200);
        }

        return new JsonResponse(["message" =>"request not valid"],400);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->find($id);

        if ($user === null)
        {
            return new JsonResponse(['message' => sprintf('User with id %s not found', $id)],404);
        }

        $em->getRepository(Quiz::class)->deleteBy(['user' => $user->getId()]);
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null,204);
    }
}