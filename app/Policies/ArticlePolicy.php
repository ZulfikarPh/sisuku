<?php
namespace App\Policies;
use App\Models\Article;
use App\Models\User;
class ArticlePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Article $article): bool { return $user->rayon_id === $article->rayon_id; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Article $article): bool { return $user->rayon_id === $article->rayon_id; }
    public function delete(User $user, Article $article): bool { return $user->rayon_id === $article->rayon_id; }
}
