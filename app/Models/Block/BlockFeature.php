<?php

namespace App\Models\Block;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasSection;
use App\Helpers\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * App\Models\Block\BlockFeature
 *
 * @property int $id
 * @property int $user_id
 * @property int $block_id
 * @property int|null $parent_id
 * @property int $order
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \App\Models\Block\Block $block
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $children
 * @property-read int|null $children_count
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\Block\BlockFeature|null $parent
 * @property-read \App\Models\Block\BlockFeatureLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Block\BlockFeatureLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\Block\BlockFeature|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Block\BlockFeature[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature active()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature listsTranslations(string $translationField)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature notTranslatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature translated()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature translatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature translations()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereBlockId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereOrder($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereStatus($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereUserId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|BlockFeature whereAdminId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @property-read \Awcodes\Curator\Models\Media|null $cover_image
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
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @mixin \Eloquent
 */
class BlockFeature extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Translatable, HasAuthor, Auditable, HasStatus, HasMedia, \App\Helpers\HasTranslations, HasRecursiveRelationships;


    public $translationModel = BlockFeatureLang::class;

    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public array $translatedAttributes = [
        'image_id', 'title', 'second_title', 'description'
    ];

    public array $upload_attributes = [
        'image_id'
    ];


    public function block(){
        return $this->belongsTo(Block::class);
    }

    public function children(){
        return $this->hasMany(BlockFeature::class, 'parent_id', 'id')
            ->whereNotNull('parent_id')
            ->with('children');
    }
}
