<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::ARTICLE_VIEW);
    }

    public function view(User $user, Article $model): bool
    {
        return $user->hasPermission(PermissionEnum::ARTICLE_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission(PermissionEnum::ARTICLE_CREATE);
    }

    public function update(User $user, Article $model): bool
    {
        return $user->hasPermission(PermissionEnum::ARTICLE_EDIT);
    }

    public function delete(User $user, Article $model): bool
    {
        return $user->hasPermission(PermissionEnum::ARTICLE_DELETE);
    }
}
