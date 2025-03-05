<?php

namespace App\Helpers;

use App\Settings\Site;
use Illuminate\Support\Str;

trait HasSearch
{
    private static array $models = [
        'Activity' => [
            'route' => 'extra-curricular-activities.show',
            'param' => 'extra_curricular_activity',
            'fields' => [
                'title',
                'description',
                'content'
            ]
        ],
        'Award' => [
            'route' => 'about-iec.awards-and-accreditations',
            'param' => null,
            'fields' => [
                'title',
                'description'
            ]
        ],
        'Facility' => [
            'route' => 'facilities.show',
            'param' => 'facility',
            'fields' => [
                'title',
                'description'
            ]
        ],
        'Program' => [
            'route' => 'educational-programs.show',
            'param' => 'educational_program',
            'fields' => [
                'title',
                'description'
            ]
        ],
        'SummerCamp' => [
            'route' => 'summer-camp.show',
            'param' => 'summer_camp',
            'fields' => [
                'title',
                'description',
                'content'
            ]
        ],
        'News' => [
            'route' => 'latest-news.show',
            'param' => 'latest_news',
            'fields' => [
                'title',
                'description',
                'content'
            ]
        ],
        'Page' => [
            'route' => 'page.show',
            'param' => 'page',
            'fields' => [
                'title',
                'description',
                'content'
            ]
        ]
    ];
    public static function results($model){
        $query = self::where(function($query) use ($model){
            foreach(self::$models[$model]['fields'] as $key){
                $query->orWhereTranslationLike($key, '%'.request('search', '').'%');
            }
        });

        $results = clone $query->limit(app(Site::class)->search_page_size * request('page', 1))->get();
        $has_more = $query->count() > $results->count();
        return [
            'results' => $results,
            'has_more' => $has_more,
        ];
    }

    public function getSearchUrl(){
        $model_name = \Str::afterLast(self::class, '\\');
        $route = self::$models[$model_name]['route'];
        $param = self::$models[$model_name]['param'];

        if ($this->sections?->count())
            return route($route, ['section' => $this->sections->first(), $param ? [$param => $this] : []]);
        return route($route, $param ? [$param => $this] : []);
    }
}