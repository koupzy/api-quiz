<?php
namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Oka\PaginationBundle\Exception\SortAttributeNotAvailableException;
use Oka\PaginationBundle\Service\PaginationManager;
use Oka\PaginationBundle\Util\PaginationResultSet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function listAction(Request $request)
    {
        /** @var PaginationManager $paginationManager */
        $paginationManager = $this->get('oka_pagination.manager');

        try {
            /** @var PaginationResultSet $paginationResultSet */
            $paginationResultSet = $paginationManager->paginate('category', $request, [], ['name' => 'ASC']);

            return new JsonResponse($this->get('jms_serializer')->toArray($paginationResultSet), 200);
        } catch (SortAttributeNotAvailableException $e) {
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
        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'name' => new Assert\Required([
                new Assert\NotBlank(), new Assert\NotNull()
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);

        if ($errors->count() === 0) {
            /** @var EntityManager $em */
            $em = $this->get('doctrine.orm.entity_manager');

            $category = new Category();
            $category->setName($requestContent['name']);
            $errors = $validator->validate($category);

            if ($errors->count() === 0) {
                $em->persist($category);
                $em->flush();

                return new JsonResponse($this->get('jms_serializer')->toArray($category), 201);
            }

            return new JsonResponse([
                'message' => sprintf('Category with name "%s" already exists.', $requestContent['name'])
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
        $category = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Category')->find($id);

        if ($category === null)
        {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }
        return new JsonResponse($this->get('jms_serializer')->toArray($category),200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateAction($id, Request $request)
    {
        $requestContent = json_decode($request->getContent(), true);
        $validator = $this->get('validator');
        $constraints = new Assert\Collection([
            'name' => new Assert\Required([
                new Assert\NotBlank(), new Assert\NotNull()
            ])
        ]);

        /** @var ConstraintViolationListInterface $errors */
        $errors = $validator->validate($requestContent, $constraints);



            if ($errors->count() === 0)
            {
                /** @var EntityManager $em */
                $em = $this->get('doctrine.orm.entity_manager');

                if (!$category = $em->getRepository('AppBundle:Category')->find($id)) {
                    return new JsonResponse(['message' => 'Category not found.'], 404);
            }
                $category->setName($requestContent['name']);
                $errors = $validator->validate($category);

                if ($errors->count() === 0)
                {
                    $em->flush();
                    return new JsonResponse($this->get('jms_serializer')->toArray($category), 200);
                }

                return new JsonResponse([
                    'message' => sprintf('Category with label "%s" already exists.', $requestContent['name'])
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
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (null === $category)
        {
            return new JsonResponse(['message' => 'Category not found'], 404);
        }

            $em->remove($category);
            $em->flush();

        return new JsonResponse(null, 204);
    }
}