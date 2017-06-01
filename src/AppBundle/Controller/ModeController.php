<?php
/**
 * Created by PhpStorm.
 * User: peflyn
 * Date: 26/05/17
 * Time: 01:54
 */

namespace AppBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Mode;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;


/**
 * Class ModeController
 * @package AppBundle\Controller
 */
class ModeController extends Controller
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
            $paginationResultSet = $paginationManager->paginate('mode', $request, [], ['label' => 'ASC']);

            return new JsonResponse($this->get('jms_serializer')->toArray($paginationResultSet), 200);
        }
        catch (SortAttributeNotAvailableException $e)
        {
            return new JsonResponse(['Message' => $e->getMessage()], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function createAction(Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
           'label' => new Assert\Required([
               new Assert\NotNull(),
               new Assert\NotBlank()
           ])
        ]);


        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0 )
        {

            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $mode = new Mode();
            $mode->setLabel($requestContent['label']);
            $errors = $validator->validate($mode);

            if ($errors->count() === 0)
            {
                $em->persist($mode);
                $em->flush();

                return new JsonResponse($this->get('jms_serializer')->toArray($mode),201);
            }
            return new JsonResponse([
                'message' => sprintf('Mode with name "%s" already exists.', $requestContent['name'])
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

    /**
     * @param $id
     * @return Response
     */
    public function readAction($id)
    {
        $singleResult = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Mode')
            ->find($id);
        if ($singleResult === null)
        {
            return new JsonResponse(['message' => 'Mode not found'], 404);
        }
        return new JsonResponse($this->get('jms_serializer')->toArray($singleResult),200);
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
            'label'=> new Assert\Required([
                new Assert\NotBlank(),
                new Assert\NotNull()
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0)
        {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            if (!$mode = $em->getRepository('AppBundle:Mode')->find($id)) {
                return new JsonResponse(['message' => 'Mode not found.'], 404);
            }

            $mode->setLabel($requestContent['label']);
            $errors = $validator->validate($mode);

            if ($errors->count() === 0)
            {
                $em->flush();
                return new JsonResponse($this->get('jms_serializer')->toArray($mode), 200);
            }

            return new JsonResponse([
                'message' => sprintf('Mode with label "%s" already exists.', $requestContent['label'])
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

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $mode = $em->getRepository('AppBundle:Mode')
            ->find($id);

        if (null === $mode)
        {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }
            $em->remove($mode);
            $em->flush();

        return new JsonResponse(null, 204);
    }

}