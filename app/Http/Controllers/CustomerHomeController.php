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
        if ($query) {
            $query = $this->removeAccents($query);
        }
    
        return Article::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->whereRaw("LOWER(CONVERT(title USING utf8mb4)) COLLATE utf8mb4_unicode_ci LIKE LOWER(CONVERT(? USING utf8mb4)) COLLATE utf8mb4_unicode_ci", ["%{$query}%"])
                         ->orWhereRaw("LOWER(CONVERT(content USING utf8mb4)) COLLATE utf8mb4_unicode_ci LIKE LOWER(CONVERT(? USING utf8mb4)) COLLATE utf8mb4_unicode_ci", ["%{$query}%"]);
        })->paginate($query ? 9 : 6);
    }
    
    private function removeAccents($string)
    {
        $unwanted_array = [
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 
            'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 
            'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 
            'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
        ];
        return strtr($string, $unwanted_array);
    }
    
}
