<?php

namespace App\Models\Category;

use App\Filament\Helpers\Translatable;
use App\Helpers\ApiResponse;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasSlug;
use App\Helpers\HasStatus;
use App\Helpers\HasTranslations;
use App\Helpers\WeightedModel;
use App\Models\Merchant\Merchant;
use App\Models\SubCategory\SubCategory;
use Filament\Tables\Columns\Concerns\HasWeight;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Category\Category
 *
 * @property int $id
 * @property int $admin_id
 * @property int|null $image_id
 * @property int $weight
 * @property int $status
 * @property string $slug
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SubCategory> $subcategories
 * @property-read int|null $subcategories_count
 * @property-read \App\Models\Category\CategoryLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category\CategoryLang> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder|Category listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTranslation(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category withoutTrashed()
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SubCategory> $sub_categories
 * @property-read int|null $sub_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Merchant> $merchant
 * @property-read int|null $merchant_count
 * @mixin \Eloquent
 */
class Category extends WeightedModel implements Auditable
{
    use HasFactory, SoftDeletes, HasMedia, \OwenIt\Auditing\Auditable, HasAuthor, HasStatus, HasSlug, Translatable, HasTranslations, HasWeight, ApiResponse;
    public $translationModel = CategoryLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public array $translatedAttributes = [
        'title'
    ];

    public function sub_categories(){
        return $this->hasMany(Subcategory::class);
    }

    public function merchant(){
        return $this->hasMany(Merchant::class);
    }
}
