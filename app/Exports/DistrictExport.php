<?php

namespace App\Exports;

use App\Helpers\BaseExport;

class DistrictExport extends BaseExport
{
    protected array $exclude = [
        'weight', 'slug', 'status', 'promote',
        'image', 'inner_image', 'id', 'features',
        'link', 'buttons', 'bullets', 'form_type'
    ];
}
