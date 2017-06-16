<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 08/06/17
 * Time: 10:01
 */

namespace AppBundle\Model;

use AppBundle\Entity\Category;
use AppBundle\Entity\Mode;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;


/**
 * Class QuizManager
 * @package AppBundle\Model
 */
abstract class QuizManager implements QuizManagerInterface
{
    /**
     * @param Request $request
     * @param User $user
     * @param Mode $mode
     * @param Category $category
     * @return JsonResponse
     */
    public function create(Request $request, User $user, Mode $mode, Category $category)
    {
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
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $quiz = new Quiz();

            if (isset($requestContent['user']))
            {
                if (isset($requestContent['created_at']))
                {
                $quiz->setCreatedAt(new \DateTime('now'));
                }

                if (isset($requestContent['mode']))
                {
                    $quiz->setMode($requestContent['mode']);
                }

                if (isset($requestContent['category']))
                {
                    $quiz->setCategory($requestContent['category']);
                }

                $em->persist($quiz);
                $em->flush();

                return new JsonResponse($this->get('jms_serializer')->toArray($quiz),201);

            }
            else
            {
                return new JsonResponse(['message' => 'User not logged'],400);
            }


        }

    }

    public function start()
    {

        // TODO: Implement start() method.
    }

    public function pause()
    {
        // TODO: Implement pause() method.
    }


}