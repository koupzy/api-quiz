<?php
/**
 * Created by PhpStorm.
 * User: yannick
 * Date: 23/05/17
 * Time: 16:15
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\BrowserKit\Request;
use AppBundle\Model\QuizManager;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class QuizController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @param $user
     * @return JsonResponse
     */
    public function createAction(Request $request, $id)
    {
        ///** @var User $user */
        //$user=$this->getUser();

       /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $user= $em->getRepository('AppBundle:User')
            ->find($id);

        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'created_at' => new Assert\DateTime([new Assert\NotBlank(), new Assert\Required()]),
            'mode' => new Assert\Required([new Assert\All([
                new Assert\Collection([
                    'label' => new Assert\NotNull([ new Assert\NotBlank()])
                ])
            ])]),

            'category' => new Assert\Required([new Assert\Collection([
                'name' => new Assert\NotBlank([ new Assert\NotNull()])])]),

            'user' => new Assert\Required([new Assert\Collection([
                'lastName'=> new Assert\NotBlank([new Assert\NotNull()]),
                'firstName'=> new Assert\NotBlank([new Assert\NotNull()]),
                'userName'=> new Assert\NotBlank([new Assert\NotNull()]),
            ])])

        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->Validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            if (isset($requestContent['user']))
            {
                QuizManager::create($user, $requestContent['category'], $requestContent['mode'], $requestContent['']);
                return new JsonResponse(['Message' => 'Quiz created'], 201);
            }
        }
        else{
          $exception =  $this->createNotFoundException('The product does not exist');

          return new JsonResponse($exception, 400);

        }

    }

    public function pauseAction()
    {

    }


}