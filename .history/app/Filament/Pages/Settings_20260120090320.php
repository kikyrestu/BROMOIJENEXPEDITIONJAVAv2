<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use App\Models\Setting;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Concerns\InteractsWithFormActions;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum | string | null $navigationGroup = 'System';

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->form->fill($settings);
        
        $this->cacheInteractsWithFormActions();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Settings')
                    ->tabs([
                        // 1. Provider Details
                        \Filament\Schemas\Components\Tabs\Tab::make('Provider Details')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('provider_name')
                                    ->label('Provider Name')
                                    ->required(),
                                \Filament\Forms\Components\DatePicker::make('member_since')
                                    ->label('Member Since'),
                                \Filament\Forms\Components\TextInput::make('provider_phone')
                                    ->label('Provider Phone')
                                    ->tel(),
                                \Filament\Forms\Components\TextInput::make('provider_email')
                                    ->label('Provider Email')
                                    ->email(),
                            ])->columns(2),

                        // 2. General Settings
                        \Filament\Schemas\Components\Tabs\Tab::make('General')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name / Brand')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('site_tagline')
                                    ->label('Site Tagline'),
                                \Filament\Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings'),
                                \Filament\Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->acceptedFileTypes(['image/x-icon', 'image/png']),
                            ])->columns(2),

                        // 3. SEO Default Settings
                        \Filament\Schemas\Components\Tabs\Tab::make('SEO Defaults')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('default_meta_title')
                                    ->label('Default Meta Title'),
                                \Filament\Forms\Components\Textarea::make('default_meta_description')
                                    ->label('Default Meta Description')
                                    ->rows(2),
                                \Filament\Forms\Components\TextInput::make('site_url')
                                    ->label('Site URL')
                                    ->url(),
                                \Filament\Forms\Components\FileUpload::make('default_og_image')
                                    ->label('Default OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings'),
                            ]),

                        // 4. Search Engine Verification
                        \Filament\Schemas\Components\Tabs\Tab::make('Verification')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Google Search Console')
                                    ->schema([
                                        \Filament\Forms\Components\Radio::make('google_verification_method')
                                            ->options([
                                                'meta' => 'Meta Tag',
                                                'file' => 'HTML File',
                                            ])
                                            ->default('meta'),
                                        \Filament\Forms\Components\Textarea::make('google_verification_code')
                                            ->label('Verification Code / Tag')
                                            ->rows(2),
                                    ]),
                                \Filament\Schemas\Components\Section::make('Bing Webmaster Tools')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('bing_verification_code')
                                            ->label('Bing Verification Code'),
                                    ]),
                            ]),

                        // 5. Template Selection
                        \Filament\Schemas\Components\Tabs\Tab::make('Appearance')
                            ->schema([
                                \Filament\Forms\Components\Select::make('active_template')
                                    ->label('Active Template')
                                    ->options([
                                        'default' => 'Modern Cards (Default)',
                                        'gotur' => 'GoTur Travel Agency (Professional)',
                                        'custom' => 'Custom (Coming Soon)',
                                    ])
                                    ->default('gotur')
                                    ->required(),
                            ]),

                        // 5b. Header Button
                        \Filament\Schemas\Components\Tabs\Tab::make('Header Button')
                            ->schema([
                                \Filament\Forms\Components\Toggle::make('header_button_show')
                                    ->label('Show Header Button')
                                    ->default(true)
                                    ->helperText('Toggle the CTA button in the navigation header.'),
                                \Filament\Forms\Components\TextInput::make('header_button_text')
                                    ->label('Button Text')
                                    ->default('Get in Touch')
                                    ->placeholder('Get in Touch'),
                                \Filament\Forms\Components\TextInput::make('header_button_url')
                                    ->label('Button URL')
                                    ->default('#book')
                                    ->placeholder('#book or https://...'),
                            ]),

                        // 6. System Settings
                        \Filament\Schemas\Components\Tabs\Tab::make('System')
                            ->schema([
                                \Filament\Forms\Components\Toggle::make('maintenance_mode')
                                    ->label('Maintenance Mode'),
                                \Filament\Forms\Components\Toggle::make('auto_approve_testimonials')
                                    ->label('Auto-approve Testimonials'),
                                \Filament\Forms\Components\Toggle::make('email_notifications')
                                    ->label('Email Notifications (New Booking)'),
                            ]),
                        
                        // 7. Backup & Export (Placeholder UI)
                        \Filament\Schemas\Components\Tabs\Tab::make('Backup')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('backup_info')
                                    ->label('Backup Management')
                                    ->content('Create and restore backups of your database and files.'),
                                \Filament\Schemas\Components\Actions::make([
                                    \Filament\Actions\Action::make('create_backup')
                                        ->label('Create Complete Backup (.mswbak)')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()->title('Backup Started (Simulated)')->success()->send();
                                        }),
                                    \Filament\Actions\Action::make('restore_backup')
                                        ->label('Restore from Backup')
                                        ->icon('heroicon-o-arrow-up-tray')
                                        ->color('danger')
                                        ->requiresConfirmation()
                                        ->action(function () {
                                            \Filament\Notifications\Notification::make()->title('Restore Feature Coming Soon')->warning()->send();
                                        }),
                                ]),
                            ]),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::pages/dashboard.actions.filter.label') === 'Filter' ? 'Save Changes' : 'Save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => 'general']
                );
            }

            Notification::make() 
                ->success()
                ->title(__('Saved successfully'))
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }
}
