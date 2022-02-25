<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use OpenFoodFacts\Document;

class FoodDTO
{
    const GENERIC_NAME_FR = 'generic_name_fr';
    const CODE = 'code';
    const BRANDS = 'brands';
    const INGREDIENTS_TEXT_FR = 'ingredients_text_fr';
    const ALLERGENS_TAGS = 'allergens_tags';
    const NUTRISCORE_GRADE = 'nutriscore_grade';
    const NUTRIMENTS = 'nutriments';
    const CATEGORIES = 'categories';
    private $name;
    private $ean;
    private $brand;
    private $ingredients;
    private $allergens;
    private $nutriscore;
    private $nutrional_values;
    private $categories;
    private $data;

    public function __construct(Document $document)
    {
        $this->data = $document->getData();

        if (key_exists(self::GENERIC_NAME_FR, $this->data)) {
            $this->name = $this->data[self::GENERIC_NAME_FR];
            $this->ean = $this->data[self::CODE];
            $this->brand = $this->data[self::BRANDS];
            $this->ingredients = $this->data[self::INGREDIENTS_TEXT_FR];
            $this->allergens = $this->data[self::ALLERGENS_TAGS];
            $this->nutriscore = $this->data[self::NUTRISCORE_GRADE];
            $this->nutrional_values = $this->data[self::NUTRIMENTS];
            $this->categories = $this->data[self::CATEGORIES];
        }
    }

    public function getFoodData(): array
    {
        return $this->data;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @Groups("api")
     * @return array
     */
    public function getAllergens(): array
    {
        return $this->allergens;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getNutriscore()
    {
        return $this->nutriscore;
    }

    /**
     * @Groups("api")
     * @return array
     */
    public function getNutrionalValues(): array
    {
        return $this->nutrional_values;
    }

    /**
     * @Groups("api")
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }
}