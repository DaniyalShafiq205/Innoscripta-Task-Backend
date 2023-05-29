<?php

namespace App\Repository\Repositories;

use App\Models\User;
use App\Models\Author;
use App\Models\Source;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Repository\BaseRepositoryInterface;


class BaseRepository implements BaseRepositoryInterface
{
    public function getAll()
    {
        $sources = Source::query()
            ->select('id', 'name')
            ->get();

        $categories = Category::query()
            ->select('id', 'name')
            ->get();

        $authors = Author::query()
            ->select('id', 'name')
            ->get();

        $data = [
            'sources' => $sources,
            'categories' => $categories,
            'authors' => $authors,
        ];

        return response()->json($data);
    }



    public function addToUser($request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $user = User::findOrFail($userId);
        //         dump( $user->sources());
        // dd($this->model === Author::class);

        $sourcesIds=$request->sources;
        $categoriesIds=$request->categories;
        $authorsIds=$request->authors;



            $user->sources()->detach();
            $user->sources()->syncWithoutDetaching($sourcesIds);

            $user->categories()->detach();
            $user->categories()->syncWithoutDetaching($categoriesIds);
            
            $user->authors()->detach();
            $user->authors()->syncWithoutDetaching($authorsIds);



        return response()->json(['message' => 'Successfully updated user associations'], 200);
    }

    // Implement other methods
}
