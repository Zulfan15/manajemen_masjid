<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{

    /** -----------------------------------------
     *  HANDLE CATEGORY (existing / new)
     * ----------------------------------------- */
    private function handleCategory(Request $request)
    {
        // Jika user memilih kategori baru
        if ($request->category_id === "new") {
            if ($request->filled('new_category')) {
                $category = Category::firstOrCreate([
                    'name' => $request->new_category
                ]);
                return $category->id;
            }
        }

        // Jika user memilih kategori lama
        if (!empty($request->category_id)) {
            return $request->category_id;
        }

        // Default fallback (tidak ada kategori)
        return null;
    }


    /** FORM TAMBAH */
    public function create()
    {
        return view('modules.informasi.form', [
            'type'        => 'artikel',
            'data'        => null,
            'categories'  => Category::all(),
            'route_store' => route('informasi.artikel.store'),
        ]);
    }


    /** SIMPAN ARTIKEL */
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|max:255',
            'content'      => 'required',
            'category_id'  => 'nullable',
            'new_category' => 'nullable|string|max:255',
            'author_name'  => 'nullable|string|max:255',
            'thumbnail'    => 'nullable|image|max:2048',
        ]);

        // Ambil ID kategori final
        $categoryId = $this->handleCategory($request);

        // Prevent category_id null (jika wajib)
        if ($categoryId === null) {
            return back()->withErrors(['Kategori wajib dipilih atau ditambahkan.'])->withInput();
        }

        $thumb = null;
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail')->store('articles', 'public');
        }

        Article::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . time(),
            'content'      => $request->content,
            'category_id'  => $categoryId,
            'author_name'  => $request->author_name ?? Auth::user()->name,
            'thumbnail'    => $thumb,
            'published_at' => now(),
        ]);

        return redirect()->route('informasi.index')
                         ->with('success', 'Artikel berhasil ditambahkan.');
    }


    /** FORM EDIT */
    public function edit($id)
    {
        $data = Article::findOrFail($id);

        return view('modules.informasi.form', [
            'type'         => 'artikel',
            'data'         => $data,
            'categories'   => Category::all(),
            'route_update' => route('informasi.artikel.update', $id),
        ]);
    }


    /** UPDATE ARTIKEL */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'        => 'required|max:255',
            'content'      => 'required',
            'category_id'  => 'nullable',
            'new_category' => 'nullable|string|max:255',
            'author_name'  => 'nullable|string|max:255',
            'thumbnail'    => 'nullable|image|max:2048'
        ]);

        $item = Article::findOrFail($id);

        $categoryId = $this->handleCategory($request);

        if ($categoryId === null) {
            return back()->withErrors(['Kategori wajib dipilih atau ditambahkan.'])->withInput();
        }

        // Jika thumbnail diganti
        if ($request->hasFile('thumbnail')) {
            $item->thumbnail = $request->file('thumbnail')->store('articles', 'public');
        }

        $item->update([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $categoryId,
            'author_name' => $request->author_name,
        ]);

        return redirect()->route('informasi.index')
                         ->with('success', 'Artikel berhasil diperbarui.');
    }


    /** DELETE */
    public function destroy($id)
    {
        Article::findOrFail($id)->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
