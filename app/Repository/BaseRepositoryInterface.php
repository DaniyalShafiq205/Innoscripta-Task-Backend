<?php

namespace App\Repository;

interface BaseRepositoryInterface
{
    public function getAll();
    // public function getById($id);
    public function addToUser($ids);
    // public function removeFromUser($ids);
    // Add other specific methods for sources
}
