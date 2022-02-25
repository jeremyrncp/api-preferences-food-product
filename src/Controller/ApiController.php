<?php

namespace App\Controller;

use App\DTO\QueryFoodDTO;
use App\Service\Api\ProductFoodApiService;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ApiController extends AbstractController
{
    /**
     * @Route("/api/food", name="api_food")
     */
    public function index(Request $request): Response
    {
        $serializer = $this->getSerializer();

        $productFoodApiService = new ProductFoodApiService(new \OpenFoodFacts\Api('food','fr'));

        return new Response(
            $serializer->serialize(
                $productFoodApiService->computeQueryFood(new QueryFoodDTO($request)),
                'json',
                ['groups'=> 'api']
            ),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @return Serializer
     */
    private function getSerializer(): Serializer
    {
        $classMetaDataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetaDataFactory)];

        return new Serializer($normalizers, $encoders);
    }
}
