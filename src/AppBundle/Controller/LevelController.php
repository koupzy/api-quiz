<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Level;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Symfony\Component\Validator\Constraints as Assert;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class LevelController
 * @package AppBundle\Controller
 * @author Ange Paterson
 */
class LevelController extends Controller
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
            $paginationResultSet = $paginationManager->paginate(Level::class, $request, [], ['label' => 'ASC']);

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
    public function createAction(Request $request)
    {

        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'label' => new Assert\Required([
                new Assert\NotBlank(), new Assert\NotNull()
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            $level = new Level();
            $level->setLabel($requestContent['label']);
            $errors = $validator->validate($level);

            if ($errors->count() === 0) {
                $em->persist($level);
                $em->flush();

                return new JsonResponse($this->get('jms_serializer')->toArray($level), 201);
            }

            return new JsonResponse([
                'message' => sprintf('Level with label "%s" already exists.', $requestContent['label'])
            ], 409);
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
     * @param $id
     * @return JsonResponse
     */
    public function readAction($id)
    {
        $level = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Level')->find($id);

        if ($level === null)
        {
            return new JsonResponse(['message' => 'Level not found'], 404);
        }
        return new JsonResponse($this->get('jms_serializer')->toArray($level),200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'label' => new Assert\Required([
                new Assert\NotBlank(), new Assert\NotNull()
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            if (!$level = $em->getRepository('AppBundle:Level')->find($id)) {
                return new JsonResponse(['message' => 'Level not found.'], 404);
            }
            $level->setLabel($requestContent['label']);
            $errors = $validator->validate($level);

            if ($errors->count() === 0)
            {
                $em->flush();
                return new JsonResponse($this->get('jms_serializer')->toArray($level), 200);
            }

            return new JsonResponse([
                'message' => sprintf('Level with label "%s" already exists.', $requestContent['label'])
                //extra' =>
            ], 409);
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

    public function deleteAction($id)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine.orm.entity_manager');
        $level = $em->getRepository('AppBundle:Level')->find($id);

        if ($level === null)
        {
            return new JsonResponse(['message' => 'Level not found'], 404);
        }

        $em->getRepository(Question::class)->detachChildForLevel($level);
        $em->getRepository(Quiz::class)->detachAllChild($level);
        $em->remove($level);
        $em->flush();

        return new JsonResponse(null, 204);
    }

}