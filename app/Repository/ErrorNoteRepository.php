<?php
namespace App\Repository;

use App\Models\Error_note;

class ErrorNoteRepository extends Repository{


    public function model()
    {
        return new Error_note();
    }

    public function storage($data)
    {
        $this->model->create($data);
    }
}