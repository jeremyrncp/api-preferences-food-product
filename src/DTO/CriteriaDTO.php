<?php

namespace App\DTO;

class CriteriaDTO implements CriteriaInterface
{
    private array $list;
    private string $name;

    public function getList(): array
    {
        return $this->list;
    }

    public function getName(): string
    {
       return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setList(array $list)
    {
        $this->list = $list;
        return $this;
    }
}
