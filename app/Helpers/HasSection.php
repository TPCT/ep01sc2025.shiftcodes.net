<?php

namespace App\Helpers;

use App\Models\Section;

trait HasSection
{
    public ?array $section_ids = null;

    public function initializeHasSection(){
        $this->append('section_ids');
        $this->section_ids = Section::pluck('id', 'title')->all();
    }

    public function getSectionIdsAttribute(): ?array
    {
        return $this->sections()->pluck('section_id')->all() ?: Section::pluck('id')->all();
    }

    public static function bootHasSection(): void{
        self::created(function (self $model){
            foreach ($model->section_ids as $section_id){
                $model->addSection($section_id);
            }
        });

        if (!in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            static::addGlobalScope('section', function($builder){
                if (request()->route()->hasParameter('section'))
                    $builder->whereHas('sections', function($query){
                        $section = request()->route()->parameter('section');
                        $section = Section::whereSlug($section instanceof Section ? $section->slug : $section)->firstOrFail();
                        $query->where('section_id', $section->id);
                    });

            });
    }

    public function withSection($section){
        return $this->whereHas('sections', function($query) use ($section){
            $section = Section::active()->whereSlug($section)->firstOrFail();
            $query->where("section_id", $section->id);
        });
    }

    public function scopeSection($query, $section){
        $query->whereHas('sections', function($query) use ($section){
            $section = Section::active()->whereSlug($section)->firstOrFail();
            $query->where("section_id", $section->id);
        });
    }

    public function sections()
    {
        return $this->morphToMany(Section::class, 'model', 'sectional');
    }

    public function addSection($section_id){
        $this->sections()->attach([
            'section_id' => $section_id,
        ]);
    }
}
