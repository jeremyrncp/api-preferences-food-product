<?php

namespace App\Service\Api;

use App\DTO\CriteriaDTO;
use App\DTO\FoodDTO;
use App\DTO\QueryFoodDTO;
use OpenFoodFacts\Api;
use OpenFoodFacts\Document;

class ProductFoodApiService
{
    /** @var Document[] $documents */
    private $documents = [];

    /**
     * @var Api
     */
    private $api;

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Compute query params to obtain filtered food products
     *
     * @param QueryFoodDTO $queryFoodDTO
     * @return array|mixed
     * @throws \OpenFoodFacts\Exception\BadRequestException
     * @throws \OpenFoodFacts\Exception\ProductNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function computeQueryFood(QueryFoodDTO $queryFoodDTO)
    {
        $this->extracted($queryFoodDTO);

        $foods = $this->getFoodsInDocuments($this->documents);

        if (!empty($queryFoodDTO->brand)) {
            $foods = $this->filterFoodByBrand($queryFoodDTO->brand, $foods);
        }

        if (!empty($queryFoodDTO->category)) {
            $foods = $this->filterFoodByCategory($queryFoodDTO->category, $foods);
        }

        if (!empty($queryFoodDTO->criteria)) {
            $foods = $this->filterFoodsByCriteria($queryFoodDTO, $foods);
        }

        return $foods;
    }

    private function filterFoodByAllergens(array $allergens, array $foods)
    {
        $foodsComputed = [];

        /**
         * @var FoodDTO $food
         */
        foreach ($foods as $food) {
            foreach ($allergens as $allergen) {
                if (strpos(implode(',', $food->getAllergens()), $allergen)) {
                    $foodsComputed[] = $food;
                    break;
                }
            }
        }

        return $foodsComputed;
    }

    /**
     * @param array $ingredients
     * @param array $foods
     * @return array
     */
    private function filterFoodByIngredient(array $ingredients, array $foods)
    {
        $foodsComputed = [];

        /**
         * @var FoodDTO $food
         */
        foreach ($foods as $food) {
            foreach ($ingredients as $ingredient) {
                if (strpos($food->getIngredients(), $ingredient)) {
                    $foodsComputed[] = $food;
                    break;
                }
            }
        }

        return $foodsComputed;
    }

    /**
     * @param string $category
     * @param array $foods
     * @return array
     */
    private function filterFoodByCategory(string $category, array $foods)
    {
        $foodsComputed = [];

        /** @var FoodDTO $food */
        foreach ($foods as $food) {
            if (strpos($food->getCategories(), $category)) {
                $foodsComputed[] = $food;
            }
        }

        return $foodsComputed;
    }

    /**
     * @param string $brand
     * @param array $foods
     * @return array
     */
    private function filterFoodByBrand(string $brand, array $foods)
    {
        $foodsComputed = [];

        /** @var FoodDTO $food */
        foreach ($foods as $food) {
            if (strpos($food->getBrand(), $brand)) {
                $foodsComputed[] = $food;
            }
        }

        return $foodsComputed;
    }


    /**
     * @param array $documents
     * @return array
     */
    private function getFoodsInDocuments(array $documents)
    {
        $foods = [];

        foreach ($documents as $document) {
            $foods[] = new FoodDTO($document);
        }

        return $foods;
    }

    /**
     * @param QueryFoodDTO $queryFoodDTO
     * @return void
     * @throws \OpenFoodFacts\Exception\BadRequestException
     * @throws \OpenFoodFacts\Exception\ProductNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function extracted(QueryFoodDTO $queryFoodDTO): void
    {
        if (!empty($queryFoodDTO->ean)) {
            $this->documents[] = $this->api->getProduct($queryFoodDTO->ean);
        }

        if (empty($queryFoodDTO->ean) && !empty($queryFoodDTO->name)) {
            foreach ($this->api->search($queryFoodDTO->name) as $document) {
                $this->documents[] = $document;
            }
        }
    }

    /**
     * @param QueryFoodDTO $queryFoodDTO
     * @param $foods
     * @return array|mixed
     */
    private function filterFoodsByCriteria(QueryFoodDTO $queryFoodDTO, $foods)
    {
        /** @var CriteriaDTO $criteriaDTO */
        foreach ($queryFoodDTO->criteria as $criteriaDTO) {
            switch ($criteriaDTO->getName()) {
                case QueryFoodDTO::ALLERGENS:
                    $foods = $this->filterFoodByAllergens($criteriaDTO->getList(), $foods);
                    break;
                case QueryFoodDTO::INGREDIENTS:
                    $foods = $this->filterfoodByIngredient($criteriaDTO->getList(), $foods);
                    break;
                default:

            }
        }
        return $foods;
    }
}
