<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 31/05/17
 * Time: 16:28
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Proposition;
use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManager;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraints as Assert;


class PropositionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request){

        /** @var PaginationManager $paginationManager */
        $paginationManager = $this->get('oka_pagination.manager');

        try {

            /** @var PaginationResultSet $paginationResultSet */
            $paginationResultSet = $paginationManager->paginate(Proposition::class, $request, [], ['content' => 'ASC']);

            return new JsonResponse($this->get('jms_serializer')->toArray($paginationResultSet), 200);
        } catch (SortAttributeNotAvailableException $e) {
            return new JsonResponse([
                'massage' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request){

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');


        $validator = $this->get('validator');

        $jsonData = json_decode($request->getContent(),true);

        /** @var ConstraintViolationListInterface $error */
        $error = $validator->validate($jsonData,new Assert\Collection([
            'content' => new Assert\Required([new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type(['type'=>'string'])
            ]),
            'truth'   => new Assert\Optional(new Assert\Type(['type'=>'boolean'])),
            'point'   => new Assert\Optional([new Assert\Type(['type'=>'integer'])]),
            'question'=> new Assert\Optional([new Assert\Collection([
                'id' => new Assert\Required([new Assert\Type(['type'=>'integer'])])
            ])])
        ]));

        if (0 === $error->count()){
            $proposition = new Proposition();
            $proposition->setContent($jsonData['content']);

            if (isset($jsonData['truth'])){
                $proposition->setTruth($jsonData['truth']);
            }

            if (isset($jsonData['point'])){
                $proposition->setPoint($jsonData['point']);
            }

            if (isset($jsonData['question'])){
                if (!$question = $em->getRepository(Question::class)->find($jsonData['question']['id'])){
                    return new JsonResponse(['message'=>printf('question with id "%s" not found',$jsonData['question']['id'])],404);
                }
                $proposition->setQuestion($question);
            }

            $em->persist($proposition);
            $em->flush();
            return new JsonResponse($this->get('jms_serializer')->toArray($proposition),201);
        }else{
            return new JsonResponse(['message'=>'request not valid','property'=>$error->get(0)->getPropertyPath(),'error' => $error->get(0)->getMessage()],400);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function readAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $proposition = $em->getRepository('AppBundle:Proposition')
            ->find($request->get('id'));

        $jms = $this->get('jms_serializer');
        $json = $jms->serialize($proposition,'json');

        if (empty($proposition))
        {
            return new JsonResponse(['message' => 'User not found'],404);
        }

        return new Response($json,200,array('Content-Type'=>'application/json'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateAction(Request $request,$id){
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');
        /** @var Proposition $proposition*/
        if (!$proposition = $em->getRepository(Proposition::class)->find($id)){
            return new JsonResponse(['message'=>printf('proposition with id %s not found',$id)],404);
        }


        $validator = $this->get('validator');

        $jsonData = json_decode($request->getContent(),true);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($jsonData, new Assert\Collection([
            'content' => new Assert\Optional([new Assert\Type(['type'=>'string']),
                                              new Assert\NotNull(),
                                              new Assert\NotBlank()]),
            'truth'  => new Assert\Optional(new Assert\Type(['type'=>'boolean'])),
            'point'  => new Assert\Optional(new Assert\Type(['type'=>'integer'])),
            'question' => new Assert\Optional(new Assert\Collection([
                'id'  => new Assert\Required(new Assert\Type(['type'=>'integer']))
            ]))
        ]));

        if (0 === $errors->count()){
            if (isset($jsonData['content'])) {
                $proposition->setContent($jsonData['content']);
            }

            if (isset($jsonData['truth'])){
                $proposition->setTruth($jsonData['truth']);
            }

            if (isset($jsonData['point'])){
                $proposition->setPoint($jsonData['point']);
            }
            if (isset($jsonData['question'])){
                if (!$question = $em->getRepository(Question::class)->find($jsonData['question']['id'])){
                    return new JsonResponse(['message'=>printf('question with id "%s" not found ',$jsonData['question']['id'])],404);
                }
                $proposition->setQuestion($question);
            }
            $em->flush();
            return new JsonResponse($this->get('jms_serializer')->toArray($proposition),201);
        }else{
            return new JsonResponse(["message" =>"request not valid", 'property' => $errors->get(0)->getPropertyPath(),  'error' => $errors->get(0)->getMessage()],400);

        }

    }

    /**
     * @param $id
     * @return JsonResponse
     * @internal param Request $request
     */
    public function deleteAction($id){

        /** @var EntityManager  $em */
        $em = $this->get('doctrine.orm.entity_manager');
        if (!$proposition = $em->getRepository(Proposition::class)->find($id)){
            return new JsonResponse(['message'=>printf('proposition with id "%s"not found',$id)],404);
        }
        $em->remove($proposition);
        $em->flush();
        return new JsonResponse(null,204);
    }

}