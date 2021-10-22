<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $full_text
 * @property int|null $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static Builder|Article newModelQuery()
 * @method static Builder|Article newQuery()
 * @method static Builder|Article query()
 * @method static Builder|Article whereCategoryId($value)
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereFullText($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article whereTitle($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @method static Builder|Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'full_text', 'category_id', 'user_id', 'published_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        // пользователь аутентифициролся и не являеться админом - фильтруем только его записи
        //  для админа показываем все записи
        if (auth()->check() && !auth()->user()->is_admin) {
            static::addGlobalScope('user', function (Builder $builder) {
                $builder->where('user_id', auth()->id());
            });
        }
    }
}
