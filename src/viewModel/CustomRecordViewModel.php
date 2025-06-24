<?php

namespace PocketBase\ViewModel;

class CustomRecordViewModel extends RecordViewModel
{
    public function __construct(array $data)
    {
        foreach ($data as $key => $item){
            $this->$key = $item;
        }

        parent::__construct($data);
    }
}