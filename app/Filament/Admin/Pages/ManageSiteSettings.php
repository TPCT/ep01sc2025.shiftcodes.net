<?php

namespace App\Filament\Admin\Pages;

use App\Exports\SiteSettingsExport;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Filament\Components\TiptapEditor;
use App\Settings\Site;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Tiptap\Nodes\Text;

class ManageSiteSettings extends SettingsPage
{
    use HasPageShield;
    protected static ?string $navigationGroup = "Site Settings";

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = Site::class;

    public function getTitle(): string|Htmlable
    {
        return __("Site Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("Site Settings");
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('Export')
                ->label(__("Export"))
                ->action(function(){
                    return \Maatwebsite\Excel\Facades\Excel::download(new SiteSettingsExport, 'site-settings.xlsx');
                })

        ];
    }

    public function form(Form $form): Form
    {
        $tabs = [];
        foreach (config('app.locales') as $locale => $language){
            $tabs[] = Forms\Components\Tabs\Tab::make($language)->schema([
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        FileUpload::make("fav_icon")
                            ->multiple(false)
                            ->label(__("Fav Icon")),
                        FileUpload::make("splash_screen_video.{$locale}")
                            ->multiple(false)
                            ->label(__("Splash screen Video"))
                            ->columnSpan(2),
                    ]),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make("email")
                            ->label(__("Email"))
                            ->email(),
                        TextInput::make("phone")
                            ->label(__("Phone"))
                    ])
            ]);
        }
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\Tabs::make()
                                ->tabs($tabs)
                                ->columnSpan(1),
                            Forms\Components\Section::make()->schema([
                                Forms\Components\Grid::make()->schema([
                                    FileUpload::make('merchant_default_image')
                                        ->label(__('Merchant Default Image'))
                                        ->multiple(false),
                                    FileUpload::make('merchant_default_cover_image')
                                        ->label(__('Merchant Default Cover Image'))
                                        ->multiple(false),
                                ])
                            ]),
                            Forms\Components\Section::make()
                                ->schema([
                                    TextInput::make('facebook_link')
                                        ->label(__("Facebook")),
                                    TextInput::make('instagram_link')
                                        ->label(__("Instagram")),
                                    TextInput::make('twitter_link')
                                        ->label(__("Twitter")),
                                    TextInput::make('linkedin_link')
                                        ->label(__("Linkedin")),
                                ])
                                ->columnSpan(1)
                                ->columns(1),
                            Forms\Components\Section::make()->schema([
                                TextInput::make('nearest_shops_distance')
                                    ->required()
                                    ->integer()
                                    ->label(__("Nearest Shops Distance")),
                            ]),
//                            Forms\Components\Section::make()->schema([
//                                Forms\Components\Checkbox::make('facebook_login')
//                                    ->label(__("Facebook Login")),
//                                Forms\Components\Checkbox::make('instagram_login')
//                                    ->label(__("Instagram Login")),
//                                Forms\Components\Checkbox::make('google_login')
//                                    ->label(__("Google Login")),
//                            ])
                        ])
                        ->columnSpan(1),
                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    TextInput::make('registration_points')
                                        ->label(__("Registration Points"))
                                        ->required()
                                        ->minValue(0)
                                        ->integer(),
                                    TextInput::make('voucher_redemption_points')
                                        ->label(__("Voucher Redemption Points"))
                                        ->required()
                                        ->minValue(0)
                                        ->integer(),
                                    TextInput::make('offer_redemption_points')
                                        ->label(__("Offer Redemption Points"))
                                        ->required()
                                        ->minValue(0)
                                        ->integer(),
                                    TextInput::make('referral_redemption_points')
                                    ->label(__("Referral Points"))
                                        ->required()
                                        ->minValue(0)
                                        ->integer(),
                                    TextInput::make('buy_voucher_points')
                                        ->label(__("Buy Voucher Points"))
                                        ->required()
                                        ->minValue(0)
                                        ->integer(),

                                ]),
                            Forms\Components\Section::make()
                                ->schema([
                                    TextInput::make("default_page_size")
                                        ->integer()
                                        ->minValue(1),
                                ]),

//                            Forms\Components\Section::make()
//                                ->schema([
//                                    TextInput::make('contact_us_mailing_list')
//                                        ->label(__("Contact Us Mailing List"))
//                                        ->helperText(__("Separate Using ,"))
//                                ]),

                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\Tabs::make()->tabs([
                                        Forms\Components\Tabs\Tab::make(__("Merchant"))->schema([
                                            TranslatableTabs::make()->localeTabSchema(fn (TranslatableTab $tab) => [
                                                TiptapEditor::make('permissions.merchant.' . $tab->makeName('header.text'))
                                                    ->label(__("Permissions[Header]"))
                                                    ->grow()
                                                    ->disableToolbarMenus()
                                                    ->disableBubbleMenus()
                                                    ->maxLength(255),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.merchant.' . $tab->makeName('items.notification.text'))
                                                        ->label(__("Permissions[Notification]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.merchant.items.notification.active')
                                                        ->label(__("Permissions[Notification]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.merchant.' . $tab->makeName('items.location.text'))
                                                        ->label(__("Permissions[Location]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.merchant.items.location.active')
                                                        ->label(__("Permissions[Location]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.merchant.' . $tab->makeName('items.camera.text'))
                                                        ->label(__("Permissions[Camera]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.merchant.items.camera.active')
                                                        ->label(__("Permissions[Camera]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.merchant.' . $tab->makeName('items.contact.text'))
                                                        ->label(__("Permissions[Contact]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.merchant.items.contact.active')
                                                        ->label(__("Permissions[Contact]")),
                                                ]),
                                            ])
                                        ]),
                                        Forms\Components\Tabs\Tab::make(__("Client"))->schema([
                                            TranslatableTabs::make()->localeTabSchema(fn (TranslatableTab $tab) => [
                                                TiptapEditor::make('permissions.client.' . $tab->makeName('header.text'))
                                                    ->label(__("Permissions[Header]"))
                                                    ->grow()
                                                    ->disableToolbarMenus()
                                                    ->disableBubbleMenus()
                                                    ->maxLength(255),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.client.' . $tab->makeName('items.notification.text'))
                                                        ->label(__("Permissions[Notification]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.client.items.notification.active')
                                                        ->label(__("Permissions[Notification]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.client.' . $tab->makeName('items.location.text'))
                                                        ->label(__("Permissions[Location]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.client.items.location.active')
                                                        ->label(__("Permissions[Location]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.client.' . $tab->makeName('items.camera.text'))
                                                        ->label(__("Permissions[Camera]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.client.items.camera.active')
                                                        ->label(__("Permissions[Camera]")),
                                                ]),
                                                Forms\Components\Grid::make(1)->schema([
                                                    TiptapEditor::make('permissions.client.' . $tab->makeName('items.contact.text'))
                                                        ->label(__("Permissions[Contact]"))
                                                        ->disableToolbarMenus()
                                                        ->disableBubbleMenus(),
                                                    Forms\Components\Toggle::make('permissions.client.items.contact.active')
                                                        ->label(__("Permissions[Contact]")),
                                                ]),
                                            ])
                                        ]),
                                    ]),
                                ])
                        ])
                        ->columnSpan(1)
                ])
            ]);
    }
}
