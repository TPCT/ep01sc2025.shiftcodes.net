<?php

namespace App\Filament\Admin\Resources\AuditResource\Pages;

use App\Filament\Admin\Resources\AuditResource;
use App\Models\Audit;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clear_audits')
                ->label(__("Clear Audits"))
                ->action(function (){
                    return Audit::truncate();
                })
        ];
    }
}
