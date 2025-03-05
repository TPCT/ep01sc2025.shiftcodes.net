<?php

namespace App\Filament\Admin\Pages;

use App\Exports\GeneralSettingsExport;
use App\Filament\Components\TextInput;
use App\Models\Language;
use App\Settings\General;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ManageGeneralSettings extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = "Site Settings";

    public function getHeaderActions(): array
    {
        return [
            Action::make('Export')
                ->label(__("Export"))
                ->action(function(){
                    return \Maatwebsite\Excel\Facades\Excel::download(new GeneralSettingsExport(), 'general-settings.xlsx');
                })

        ];
    }
    public function getTitle(): string|Htmlable
    {
        return __("General Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("General Settings");
    }



    protected static string $settings = General::class;

    public function form(Form $form): Form
    {
        $tabs = [];
        foreach (config('app.locales') as $locale => $language){
            $tabs[] = Forms\Components\Tabs\Tab::make($language)->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make("site_title.{$locale}")
                            ->label(__("Site Title") . "[{$language}]")
                            ->required(),
                        TextInput::make("site_description.{$locale}")
                            ->label(__("Site Description") . "[{$language}]"),
                        TextInput::make("site_admin_email")
                            ->label(__("Admin Email"))
                            ->email(),
                        Forms\Components\Select::make("site_country")
                            ->label(__("Country"))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->options(Arr::keyBy(\Countries::getList(), function($value){
                                return $value;
                            })),
                        Forms\Components\Select::make("site_timezone")
                            ->label(__("Timezone"))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->options(Arr::keyBy(General::getTimezones(), function($value){
                                return $value;
                            }))
                            ->default("Asia/Amman"),
                    ])
            ]);
        }
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Tabs::make()->tabs($tabs)->columnSpan(1),
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Select::make('default_locale')
                            ->default(Language::first() ?? config('app.locale'))
                            ->native(false)
                            ->options(function(){
                                return Arr::mapWithKeys(Language::get(['locale', 'language'])->toArray(), function ($value){
                                    return [$value['locale'] => $value['locale']];
                                });
                            }),
                        Forms\Components\Repeater::make('languages')
                            ->label(__("Languages"))
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('locale')
                                            ->unique(ignoreRecord: true)
                                            ->label(__("Locale"))
                                            ->required(),
                                        TextInput::make('language')
                                            ->label(__("Language"))
                                            ->unique(ignoreRecord: true)
                                            ->required(),
                                    ])
                            ])
                            ->live()
                            ->formatStateUsing(function (){
                                $languages = Language::active()->get();
                                foreach ($languages as &$language){
                                    $language['locale'] = $language->locale;
                                    $language['language'] = $language->language;
                                }
                                return $languages->toArray();
                            })
                            ->itemLabel(function($state){
                                return $state['language'];
                            })
                            ->reorderable(false)
                            ->collapsible()
                    ])->columnSpan(1)
                ])
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $supported_languages = Arr::pluck($data['languages'], 'locale');
        Language::whereNotIn('locale', $supported_languages)->delete();

        foreach ($data['languages'] ?? [] as $language){
            unset($language['created_at'], $language['updated_at'], $language['id']);
            Language::updateOrCreate(['locale' => $language['locale']], $language);
        }
        return parent::mutateFormDataBeforeSave($data);
    }
}
