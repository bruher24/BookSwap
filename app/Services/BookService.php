<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookService
{
    private BookRepository $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }

    public function create(array $data): bool
    {
        try {
            $this->bookRepository->create($data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->bookRepository->update($id, $data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function delete(int $id): bool
    {
        try {
            $this->bookRepository->delete($id);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function getAll(): Collection
    {
        return $this->bookRepository->getAll();
    }

    public function get(int $id): Book | false
    {
        try {
            $book = $this->bookRepository->get($id);
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return false;
        }
        return $book;
    }
}