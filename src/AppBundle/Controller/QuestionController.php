<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 24/05/17
 * Time: 09:20
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Proposition;
use AppBundle\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function listAction(Request $request)
    {
        $filters = [];
        $criteria = [];
        $query = $request->query;

        /** @var PaginationManager $paginationManager */
        $paginationManager = $this->get('oka_pagination.manager');

        if (null !== ($duration = $query->get('duration', null))) {
            $criteria['duration'] = (int) $duration;
            $filters[] = 'duration='.$duration;
        }

        try {
            /** @var PaginationResultSet $paginationResultSet */
            $paginationResultSet = $paginationManager->paginate('question', $request, $criteria, ['content' => 'ASC']);

            /** @var PaginationResultSet $paginationResultSet */
            //$paginationResultSet = $paginationManager->paginate(Question::class, $request, [], ['content' => 'ASC']);

            $content = array_merge(
                $paginationResultSet->toArray(), [
                    'filters' => implode($filters, ',')
                ]
            );

            return new JsonResponse($this->get('jms_serializer')->toArray($content), 200);
        } catch (SortAttributeNotAvailableException $e) {
            return new JsonResponse([
                'massage' => $e->getMessage()
            ], 400);
        }


    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $validator = $this->get('validator');
        $jsonData = json_decode($request->getContent(),true);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($jsonData, new Assert\Collection([
            'content'=> new Assert\Required([new Assert\NotBlank(), new Assert\NotNull()]),
            'duration' => new Assert\Optional([new Assert\Type(['type' => 'integer'])]),
            'multipleChoice' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
            'category' => new Assert\Optional([
                new Assert\Collection([
                    'id' => new Assert\Required([new Assert\Type(['type'=>'integer'])])
                ])
            ]),
            'propositions' => new Assert\Optional([
                new Assert\Callback(function($object, ExecutionContextInterface $context){
                    /** @var ValidatorInterface $validator */
                    $validator = $context->getValidator();

                    $errors = $validator->validate($object, new Assert\Type(['type'=>'array']));

                    if ($errors->count() === 0) {
                        $errors = $validator->validate($object, new Assert\Count(['max' => 10]));

                        if ($errors->count() === 0) {
                            $errors = $validator->validate($object, new Assert\All([
                                new Assert\Collection([
                                    'content'=>new Assert\Required([new Assert\NotBlank(),new Assert\NotNull()]),
                                    'truth'=> new Assert\Optional([new Assert\Type(['type'=>'boolean'])]),
                                    'point'=>new Assert\Optional([new Assert\Type(['type'=>'boolean'])])
                                ])
                            ]));

                            return;
                        }
                    }

                    /** @var ConstraintViolationInterface $error */
                    foreach ($errors as $error) {
                        $context->buildViolation($error->getMessageTemplate(), $error->getParameters())
                                ->atPath($error->getPropertyPath())
                                ->setInvalidValue($error->getInvalidValue())
                                ->setPlural($error->getPlural())
                                ->addViolation();
                    }
                })
            ])
        ]));



        if (0 === $errors->count()) {
            $question = new Question();
            $question->setContent($jsonData['content']);

            if (isset($jsonData['duration'])) {
                $question->setDuration($jsonData['duration']);
            }

            if (isset($jsonData['multipleChoice'])) {
                $question->setMultipleChoice($jsonData['multipleChoice']);
            }

            if (isset($jsonData['category'])) {
                if (!$category = $em->getRepository(Category::class)->find($jsonData['category']['id'])){
                    return new JsonResponse(["message" =>"category not found"],404);
                }
                $question->setCategory($category);
            }

            if (isset($jsonData['propositions'])) {
                foreach ($jsonData['propositions'] as $key => $item) {
                    $proposition = new Proposition();
                    $proposition->setContent($item['content']);

                    if (isset($item['truth'])){
                        $proposition->setTruth($item['truth']);
                    }
                    if (isset($item['point'])){
                        $proposition->setPoint($item['point']);
                    }

                    $question->addProposition($proposition);
                    $em->persist($proposition);
                }
            }


            $em->persist($question);
            $em->flush();

            return new JsonResponse($this->get('jms_serializer')->toArray($question),201);
        } else {
            return new JsonResponse(["message" =>"request not valid", 'property' => $errors->get(0)->getPropertyPath(),  'error' => $errors->get(0)->getMessage()],400);
        }


    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function readAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')
                       ->find($request->get('id'));

        $jms = $this->get('jms_serializer');
        $json = $jms->serialize($question,'json');

        if (empty($question))
        {
            return new JsonResponse(['message' => 'User not found'],404);
        }


        return new Response($json,200,array('Content-Type'=>'application/json'));
    }



    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var Question $question */
        if (!$question = $em->getRepository(Question::class)->find($id)) {
            return new JsonResponse(['message' => sprintf('Question with id "%s" not found.', $id)],404);
        }

        $jsonData = json_decode($request->getContent(),true);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $this->get('validator')->validate($jsonData, new Assert\Collection([
            'content' => new Assert\Optional([new Assert\NotBlank(), new Assert\NotNull()]),
            'duration' => new Assert\Optional(new Assert\Type(['type' => 'integer'])),
            'multipleChoice' => new Assert\Optional([new Assert\Type(['type' => 'boolean'])]),
            'category' => new Assert\Optional([
                new Assert\Collection([
                    'id' => new Assert\Required([new Assert\Type(['type' => 'integer'])])
                ])
            ]),
            'propositions' => new Assert\Optional([
                new Assert\Type(['type'=>'array']),
                new Assert\Count(['max'=>3]),
                new Assert\All([
                    new Assert\Collection([
                        'id'=> new Assert\Optional(new Assert\Type(['type'=>'integer'])),
                        'content'=>new Assert\Optional([new Assert\NotBlank(),new Assert\NotNull()]),
                        'truth'=> new Assert\Optional([new Assert\Type(['type'=>'boolean'])]),
                        'point'=>new Assert\Optional([new Assert\Type(['type'=>'boolean'])])
                    ])
                ])
            ])
        ]));



        if ($errors->count() === 0) {
            if (isset($jsonData['content'])){
                $question->setContent($jsonData['content']);
            }

            if (isset($jsonData['duration'])){
                $question->setDuration($jsonData['duration']);
            }

            if (isset($jsonData['multipleChoice'])){
                $question->setMultipleChoice($jsonData['multipleChoice']);
            }

            if (isset($jsonData['category'])) {
                if (!$category = $em->getRepository(Category::class)->find($jsonData['category']['id'])){
                    return new JsonResponse(["message" =>"category not found"],404);
                }
                $question->setCategory($category);
            }

            if (isset($jsonData['propositions'])) {
                $question->setPropositions(new ArrayCollection());

                foreach ($jsonData['propositions'] as $key => $item) {
                    if (isset($item['id'])) {
                        if (!$proposition = $em->getRepository(Proposition::class)->find($item['id'])) {
                            return new JsonResponse([
                                'message' => sprintf('Proposition with id "%s" not found.', $item['id'])
                            ], 404);
                        }
                    } else {
                        if (!isset($item['content'])) {
                            return new JsonResponse([
                                'message' => sprintf('Proposition key "%s" content not defined', $key)
                            ],400);
                        }

                        $proposition = new Proposition();
                        $em->persist($proposition);
                    }

                    if (isset($item['content'])){
                        $proposition->setContent($item['content']);
                    }
                    if (isset($item['truth'])){
                        $proposition->setTruth($item['truth']);
                    }
                    if (isset($item['point'])){
                        $proposition->setPoint($item['point']);
                    }
                    $question->addProposition($proposition);
                }
            }
            $em->flush();

            return new JsonResponse($this->get('jms_serializer')->toArray($question),200);
        }
        return new JsonResponse(["message" =>"request not valid"],400);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $question = $em->getRepository(Question::class)->find($id);

        if (null === $question) {
            return new JsonResponse(["message" => sprintf('question with id %s not found', $id)],404);
        }

        $em->getRepository(Proposition::class)->deleteBy(['question' => $question->getId()]);
        $em->remove($question);
        $em->flush();

        return new JsonResponse(null,204);
    }

}