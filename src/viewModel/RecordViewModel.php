<?php

namespace PocketBase\ViewModel;

class RecordViewModel
{
    private string $collectionName;
    private string $collectionId;
    private string $created;
    private string $updated;
    private string $id;

    public function __construct(array $data)
    {
        if(key_exists('id', $data)){
            $this->setId($data['id']);
        }
        if(key_exists('updated', $data)){
           $this->setUpdated($data['updated']);
        }
        if(key_exists('created', $data)){
            $this->setCreated($data['created']);
        }
        if(key_exists('collectionName', $data)){
            $this->setCollectionName($data['collectionName']);
        }
        if(key_exists('collectionId', $data)){
            $this->setCollectionId($data['collectionId']);
        }
    }

    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    public function setCollectionName(string $collectionName): RecordViewModel
    {
        $this->collectionName = $collectionName;
        return $this;
    }

    public function getCollectionId(): string
    {
        return $this->collectionId;
    }

    public function setCollectionId(string $collectionId): RecordViewModel
    {
        $this->collectionId = $collectionId;
        return $this;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function setCreated(string $created): RecordViewModel
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): string
    {
        return $this->updated;
    }

    public function setUpdated(string $updated): RecordViewModel
    {
        $this->updated = $updated;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): RecordViewModel
    {
        $this->id = $id;
        return $this;
    }
}