<?php

namespace App\Models\SubCategory;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Helpers\HasAuthor;
use App\Helpers\HasSlug;
use App\Helpers\HasStatus;
use App\Helpers\HasTimestamps;
use App\Helpers\HasTranslations;
use App\Helpers\WeightedModel;
use App\Models\Merchant\Merchant;
use Filament\Tables\Columns\Concerns\HasWeight;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\SubCategory\SubCategory
 *
 * @property int $id
 * @property int $category_id
 * @property int $admin_id
 * @property int|null $image_id
 * @property int $weight
 * @property int $status
 * @property string $slug
 * @property string $published_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\SubCategory\SubCategoryLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubCategory\SubCategoryLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory active()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory translated()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory withTranslation(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Merchant> $merchants
 * @property-read int|null $merchants_count
 * @mixin \Eloquent
 */
class SubCategory extends WeightedModel implements Auditable
{
    use HasFactory, ApiResponse, SoftDeletes, HasTimestamps, \OwenIt\Auditing\Auditable, HasAuthor, HasStatus, HasSlug, Translatable, HasTranslations, HasWeight;
    public $translationModel = SubCategoryLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $translatedAttributes = [
        'title'
    ];

    public function merchants(){
        return $this->belongsToMany(Merchant::class, 'merchants_sub_categories', 'sub_category_id', 'merchant_id');
    }
}
