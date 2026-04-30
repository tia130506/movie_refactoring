<?php

namespace App\Repositories;

use App\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllMovies()
    {
        return Movie::all();
    }

    public function getMovieById($id)
    {
        return Movie::findOrFail($id);
    }

    public function getMoviesPaginated($perPage = 6, $search = null)
    {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        return $query->paginate($perPage)->withQueryString();
    }

    public function getLatestMoviesPaginated($perPage = 10)
    {
        return Movie::latest()->paginate($perPage);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }

    public function updateMovie($id, array $data)
    {
        $movie = $this->getMovieById($id);
        $movie->update($data);
        return $movie;
    }

    public function deleteMovie($id)
    {
        $movie = $this->getMovieById($id);
        $movie->delete();
        return $movie;
    }
}
