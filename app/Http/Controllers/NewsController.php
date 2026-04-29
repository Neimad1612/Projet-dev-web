<?php
namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()->latest('published_at');

        // Filtre 1 : Recherche (exigé par le CDC)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre 2 : Catégorie (exigé par le CDC)
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $news = $query->paginate(9)->withQueryString();
        
        return view('public.news.index', compact('news'));
    }
}