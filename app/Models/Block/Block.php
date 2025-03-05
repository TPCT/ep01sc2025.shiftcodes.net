<?php

namespace App\Models\Block;

use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasSection;
use App\Helpers\HasStatus;
use App\Helpers\HasUploads;
use App\Models\Dropdown\Dropdown;
use App\Filament\Helpers\Translatable;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * App\Models\Block\Block
 *
 * @property int $id
 * @property array|null $buttons
 * @property string|null $bullets
 * @property int $user_id
 * @property string $published_at
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $dropdown_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $children
 * @property-read int|null $children_count
 * @property-read Dropdown|null $dropdown
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Block\BlockFeature> $features
 * @property-read int|null $features_count
 * @property-read array|null $section_ids
 * @property-read Media|null $image
 * @property-read \App\Models\Block\Block|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections
 * @property-read int|null $sections_count
 * @property-read \App\Models\Block\BlockLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Block\BlockLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\Block\Block|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\Block[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block active()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block listsTranslations(string $translationField)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block notTranslatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block section($section)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block translated()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block translatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block translations()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereBullets($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereButtons($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereDropdownId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block wherePublishedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereStatus($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereUserId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block withTranslation(?string $locale = null)
 * @property int $admin_id
 * @property int $promote_to_homepage
 * @property-read Media|null $cover_image
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block whereAdminId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Block wherePromoteToHomepage($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @mixin \Eloquent
 */
class Block extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Translatable, HasAuthor, Auditable, HasStatus, HasSection, HasMedia,  \App\Helpers\HasTranslations, HasRecursiveRelationships;

    public $translationModel = BlockLang::class;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatedAttributes = [
        'title',
        'second_title',
        'description',
        'content',
        'image_id',
        'youtube_video_id'
    ];

    protected $casts = [
        'buttons' => 'array',
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function dropdown(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dropdown::class)
            ->with('translations')
            ->where('category', Dropdown::BLOCK_CATEGORY);
    }

    public static function getCategoryList(){
        return Dropdown::whereCategory(Dropdown::BLOCK_CATEGORY)->get()->pluck('title', 'id');
    }

    public function features(){
        return $this->hasMany(BlockFeature::class, 'block_id', 'id');
    }
}
