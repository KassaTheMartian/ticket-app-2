<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTableResponse();
        }

        $articles = Article::all();
        return view('admin.articles.index', compact('articles'));
    }

    private function getDataTableResponse()
    {
        $data = Article::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'row' => $row,
                    'editRoute' => 'admin.articles.edit',
                    'deleteRoute' => 'admin.articles.destroy'
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $article = $this->createArticle($request);

        $this->saveAttachments($request, $article);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:1000',
            'content' => 'required',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,doc,docx,xls,xlsx,pdf|max:20480',
        ]);
    }

    private function createArticle(Request $request)
    {
        return Article::create($request->only('title', 'content'));
    }

    private function saveAttachments(Request $request, Article $article)
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $article->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        $attachments = $article->attachments;
        return view('customer.article_show', compact('article', 'attachments'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        $article = Article::findOrFail($id);
        $article->update($request->only('title', 'content'));

        $this->saveAttachments($request, $article);

        return redirect()->back()->with('success', 'Article updated successfully.');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}