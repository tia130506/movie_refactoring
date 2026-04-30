<?php

namespace App\Interfaces;

interface MovieRepositoryInterface
{
    public function getAllMovies();
    public function getMovieById($id);
    public function getMoviesPaginated($perPage = 6, $search = null);
    public function getLatestMoviesPaginated($perPage = 10);
    public function createMovie(array $data);
    public function updateMovie($id, array $data);
    public function deleteMovie($id);
}
