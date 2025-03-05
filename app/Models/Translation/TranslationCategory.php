<?php

namespace App\Models\Translation;

use App\Helpers\HasAuthor;
use App\Helpers\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Translation\TranslationCategory
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory translations()
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereUserId($value)
 * @property int $admin_id
 * @method static \Illuminate\Database\Eloquent\Builder|TranslationCategory whereAdminId($value)
 * @mixin \Eloquent
 */
class TranslationCategory extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, HasAuthor, HasTranslations;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public static function getTranslations($locale, $group): array{
        $group = static::query()->where('title', $group)->first();
        if (!$group)
            return [];

        return \Arr::undot(
            Translation::where('translation_category_id', $group->id)
                ->orderByDesc('updated_at')
                ->get()
                ->map(function(Translation $translation) use ($locale){
                    return \Arr::undot([
                        'key' => $translation->key,
                        'text' => $translation->translate($locale)?->content ?? $translation->key
                    ]);
                })
                ->pluck('text', 'key')
                ->toArray()
        );
    }
}
