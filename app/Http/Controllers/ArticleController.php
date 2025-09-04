<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * [DIUBAH] Method untuk menampilkan halaman daftar semua artikel dengan fitur lengkap.
     */
    public function index(Request $request)
    {
        // 1. Ambil 1 artikel terbaru sebagai "featured" untuk bagian hero
        $featuredArticle = Article::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['category', 'rayon']) // Ambil juga relasinya
            ->latest('published_at')
            ->first();

        // 2. Buat query dasar untuk daftar artikel utama
        $articlesQuery = Article::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now());

        // 3. Jika artikel "featured" ditemukan, jangan ikutkan lagi di daftar utama
        if ($featuredArticle) {
            $articlesQuery->where('id', '!=', $featuredArticle->id);
        }

        // 4. Terapkan filter berdasarkan input dari frontend
        $articlesQuery->when($request->input('search'), function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        });

        $articlesQuery->when($request->input('category'), function ($query, $category) {
            $query->where('article_category_id', $category);
        });

        // 5. Eksekusi query utama dengan paginasi
        $articles = $articlesQuery->with(['category', 'rayon'])
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString(); // withQueryString() agar filter tetap aktif saat pindah halaman

        // 6. Ambil semua kategori untuk dropdown filter di frontend
        $categories = ArticleCategory::orderBy('name')->get();

        // 7. Kirim semua data yang dibutuhkan ke view Inertia
        return Inertia::render('Articles/Index', [ // Menggunakan path 'Articles/Index' sesuai kode awal Anda
            'featuredArticle' => $featuredArticle,
            'articles' => $articles,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']), // Kirim filter aktif untuk ditampilkan lagi di form
        ]);
    }

    /**
     * Method untuk menampilkan satu artikel secara detail (Tidak ada perubahan).
     */
    public function show(Article $article)
    {
        // Keamanan: Pastikan hanya artikel yang sudah publish yang bisa diakses
        if ($article->status !== 'published' || $article->published_at > now()) {
            abort(404);
        }

        // Ambil data relasi utama
        $article->load(['category', 'tags', 'rayon']);

        // [BARU] Ambil 3 artikel lain dari kategori yang sama sebagai "Artikel Terkait"
        $relatedArticles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('article_category_id', $article->article_category_id) // Dari kategori yang sama
            ->where('id', '!=', $article->id) // Kecualikan artikel yang sedang dibaca
            ->with('category')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles, // Kirim artikel terkait ke frontend
        ]);
    }
}
