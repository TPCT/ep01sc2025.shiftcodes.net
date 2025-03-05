<?php

namespace App\Models\Menu;

use App\Filament\Helpers\Translatable;
use App\Helpers\HasAuthor;
use App\Helpers\HasMedia;
use App\Helpers\HasStatus;
use App\Helpers\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * App\Models\Menu\MenuLink
 *
 * @property int $id
 * @property int $menu_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property int $sort
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $image_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Admin $author
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $children
 * @property-read int|null $children_count
 * @property-read \Awcodes\Curator\Models\Media|null $image
 * @property-read \App\Models\Menu\Menu $menu
 * @property-read \App\Models\Menu\MenuLink|null $parent
 * @property-read \App\Models\Menu\MenuLinkLang|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu\MenuLinkLang> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Admin $user
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\Menu\MenuLink|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Menu\MenuLink[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink active()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink listsTranslations(string $translationField)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink notTranslatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink translated()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink translatedIn(?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink translations()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereImageId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereMenuId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereStatus($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereUserId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink withTranslation(?string $locale = null)
 * @property int $admin_id
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MenuLink whereAdminId($value)
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
class MenuLink extends Model implements Auditable
{
    use HasFactory, HasAuthor, \OwenIt\Auditing\Auditable, HasStatus, Translatable, HasTranslations, HasRecursiveRelationships, HasMedia;

    protected $table = 'menu_links';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $translationModel = MenuLinkLang::class;

    public array $translatedAttributes = [
        'title', 'link'
    ];

    public array $upload_attributes = [
        'image_id'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function has_children(): bool
    {
        return $this->children->count() > 0;
    }

    public function children(){
        return $this->hasMany(MenuLink::class, 'parent_id', 'id')
            ->whereNotNull('parent_id')
            ->with('children')
            ->orderBy('sort');
    }
}
