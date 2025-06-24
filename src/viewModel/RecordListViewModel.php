<?php

namespace PocketBase\ViewModel;

class RecordListViewModel extends RecordViewModel
{
    private array $items;
    private int $page;
    private int $perPage;
    private int $totalItems;
    private int $totalPages;

    public function __construct(array $data)
    {
        if (key_exists('items', $data)) {
            $this->setItems($data['items']);
        }
        if (key_exists('page', $data)) {
           $this->setPage($data['page']);
        }
        if (key_exists('perPage', $data)) {
            $this->setPerPage($data['perPage']);
        }
        if (key_exists('totalItems', $data)) {
            $this->setTotalItems($data['totalItems']);
        }
        if (key_exists('totalPages', $data)) {
            $this->setTotalPages($data['totalPages']);
        }

        parent::__construct($data);
    }

    public function setTotalItems(int $totalItems): RecordListViewModel
    {
        $this->totalItems = $totalItems;
        return $this;
    }


    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): RecordListViewModel
    {
        $this->items = $items;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): RecordListViewModel
    {
        $this->page = $page;
        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): RecordListViewModel
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = $totalPages;
    }
}