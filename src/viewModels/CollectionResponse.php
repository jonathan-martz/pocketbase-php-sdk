<?php

namespace Pb\viewModels;

class CollectionResponse
{
    public array $items;
    public int $page;
    public int $perPage;
    public int $totalItems;
    public int $totalPages;
}