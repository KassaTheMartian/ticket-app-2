<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;


class CustomerHomeController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = $request->input('query');
        $articles = $this->getArticles($query);

        return view('customer.dashboard', ['articles' => $articles, 'query' => $query]);
    }

    private function getArticles($query)
    {
        return Article::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('title', 'LIKE', "%{$query}%")
                         ->orWhere('content', 'LIKE', "%{$query}%");
        })->paginate($query ? 9 : 6);
    }
}
