<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class QueryFoodDTO
{
    const NAME = 'name';
    const EAN = 'ean';
    const BRAND = 'brand';
    const CATEGORY = 'category';
    const INGREDIENTS = 'ingredients';
    const ALLERGENS = 'allergens';


    public string $name;
    public array $criteria;
    public string $ean;
    public string $brand;
    public string $category;

    public function __construct(Request $request)
    {
        if ($request->query->has(self::NAME)) {
            $this->name = $request->query->get(self::NAME);
        }

        if ($request->query->has(self::EAN)) {
            $this->ean = $request->query->get(self::EAN);
        }

        if ($request->query->has(self::BRAND)) {
            $this->brand = $request->query->get(self::BRAND);
        }

        if ($request->query->has('' . self::CATEGORY)) {
            $this->category = $request->query->get(self::CATEGORY);
        }

        if ($request->query->has(self::INGREDIENTS)) {
            $this->criteria[] = (new CriteriaDTO())
                                    ->setName(self::INGREDIENTS)
                                    ->setList(explode(',', $request->query->get(self::INGREDIENTS)))
            ;
        }

        if ($request->query->has(self::ALLERGENS)) {
            $this->criteria[] = (new CriteriaDTO())
                ->setName(self::ALLERGENS)
                ->setList(explode(',', $request->query->get(self::ALLERGENS)))
            ;
        }
    }
}