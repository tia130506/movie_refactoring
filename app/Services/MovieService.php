<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getMoviesForHomepage($search = null)
    {
        return $this->movieRepository->getMoviesPaginated(6, $search);
    }

    public function getMovieDetail($id)
    {
        return $this->movieRepository->getMovieById($id);
    }

    public function storeMovie(array $data, $file = null)
    {
        if ($file) {
            $data['foto_sampul'] = $file->store('movie_covers', 'public');
        }

        return $this->movieRepository->createMovie($data);
    }

    public function getMoviesForData()
    {
        return $this->movieRepository->getLatestMoviesPaginated(10);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $movie = $this->movieRepository->getMovieById($id);

        if ($file) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;

            // Simpan file foto ke folder public/images
            $file->move(public_path('images'), $fileName);

            // Hapus foto lama jika ada
            if (File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $data['foto_sampul'] = $fileName;
        }

        return $this->movieRepository->updateMovie($id, $data);
    }

    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->getMovieById($id);

        // Delete the movie's photo if it exists
        if (File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        return $this->movieRepository->deleteMovie($id);
    }
}
