<?php
namespace App\DTO;

interface CriteriaInterface
{
    public function getList(): array;
    public function getName(): string;
}