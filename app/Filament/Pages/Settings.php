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
        
        // Decode JSON strings back to arrays for repeater fields
        $arrayFields = ['social_links'];
        foreach ($arrayFields as $field) {
            if (isset($settings[$field]) && is_string($settings[$field])) {
                $decoded = json_decode($settings[$field], true);
                if (is_array($decoded)) {
                    $settings[$field] = $decoded;
                }
            }
        }
        
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
                                \Filament\Forms\Components\TextInput::make('whatsapp_number')
                                    ->label('WhatsApp Number (optional override)')
                                    ->tel()
                                    ->helperText('Leave empty to use Provider Phone for WhatsApp links.'),
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
                                \Filament\Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->maxSize(5120),
                                \Filament\Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml', 'image/jpeg', 'image/webp']),
                            ])->columns(2),

                        // 3. SEO Default Settings
                        \Filament\Schemas\Components\Tabs\Tab::make('SEO Defaults')
                            ->schema([
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
                                    ->directory('settings')
                                    ->maxSize(5120),
                            ]),

                        // 4. Search Engine Verification
                        \Filament\Schemas\Components\Tabs\Tab::make('Verification')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Google Search Console')
                                    ->schema([
                                        \Filament\Forms\Components\Textarea::make('google_verification_code')
                                            ->label('Verification Code / Tag')
                                            ->helperText('Paste full meta verification tag from Google Search Console.')
                                            ->rows(2),
                                    ]),
                                \Filament\Schemas\Components\Section::make('Bing Webmaster Tools')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('bing_verification_code')
                                            ->label('Bing Verification Code'),
                                    ]),
                            ]),

                        // 5. Template & Page Heroes
                        \Filament\Schemas\Components\Tabs\Tab::make('Appearance')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Page Hero Images')
                                    ->description('Currently used by Gallery page hero. Recommended size: 1920x600px.')
                                    ->schema([
                                        \Filament\Forms\Components\FileUpload::make('hero_image_gallery')
                                            ->label('Gallery Page Hero')
                                            ->image()
                                            ->disk('public')
                                            ->directory('heroes')
                                            ->maxSize(10240)
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('16:5'),
                                    ]),
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
                                \Filament\Forms\Components\Select::make('header_button_icon')
                                    ->label('Button Icon')
                                    ->options([
                                        '' => 'No Icon',
                                        'phone' => '📞 Phone',
                                        'envelope' => '✉️ Envelope',
                                        'chat' => '💬 Chat Bubble',
                                        'calendar' => '📅 Calendar',
                                        'arrow-right' => '➡️ Arrow Right',
                                        'sparkles' => '✨ Sparkles',
                                        'rocket' => '🚀 Rocket',
                                    ])
                                    ->default(''),
                                \Filament\Forms\Components\Radio::make('header_button_icon_position')
                                    ->label('Icon Position')
                                    ->options([
                                        'left' => 'Left of Text',
                                        'right' => 'Right of Text',
                                    ])
                                    ->default('left')
                                    ->inline(),
                            ]),

                        // 5c. Social Links (Floating)
                        \Filament\Schemas\Components\Tabs\Tab::make('Social Links')
                            ->schema([
                                \Filament\Forms\Components\Toggle::make('floating_social_enabled')
                                    ->label('Enable Floating Social Bar')
                                    ->default(true)
                                    ->helperText('Show social icons + back to top button on the right side.'),
                                \Filament\Forms\Components\Repeater::make('social_links')
                                    ->label('Social Media Links')
                                    ->schema([
                                        \Filament\Forms\Components\Select::make('platform')
                                            ->label('Platform')
                                            ->options([
                                                'facebook' => 'Facebook',
                                                'instagram' => 'Instagram',
                                                'twitter' => 'Twitter/X',
                                                'linkedin' => 'LinkedIn',
                                                'github' => 'GitHub',
                                                'youtube' => 'YouTube',
                                                'tiktok' => 'TikTok',
                                                'whatsapp' => 'WhatsApp',
                                            ])
                                            ->required(),
                                        \Filament\Forms\Components\TextInput::make('url')
                                            ->label('URL')
                                            ->url()
                                            ->required()
                                            ->placeholder('https://...'),
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->label('Display Name (optional)')
                                            ->placeholder('My Facebook'),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Social Link')
                                    ->reorderable()
                                    ->collapsible(),
                            ]),

                        // 6. Custom Scripts
                        \Filament\Schemas\Components\Tabs\Tab::make('Custom Scripts')
                            ->schema([
                                \Filament\Forms\Components\Textarea::make('site_header_code')
                                    ->label('Header Code')
                                    ->rows(6)
                                    ->helperText('Injected into <head>. Use for analytics tags, pixels, or custom meta scripts.'),
                                \Filament\Forms\Components\Textarea::make('site_footer_code')
                                    ->label('Footer Code')
                                    ->rows(6)
                                    ->helperText('Injected before </body>. Use for chat widgets or deferred scripts.'),
                            ]),

                        // 7. System Settings
                        \Filament\Schemas\Components\Tabs\Tab::make('System')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('system_settings_status')
                                    ->label('Status')
                                    ->content('These controls are reserved for future integration and currently do not affect runtime behavior.'),
                                \Filament\Forms\Components\Toggle::make('maintenance_mode')
                                    ->label('Maintenance Mode')
                                    ->disabled(),
                                \Filament\Forms\Components\Toggle::make('auto_approve_testimonials')
                                    ->label('Auto-approve Testimonials')
                                    ->disabled(),
                                \Filament\Forms\Components\Toggle::make('email_notifications')
                                    ->label('Email Notifications (New Booking)')
                                    ->disabled(),
                            ]),
                        
                        // 8. Backup & Export (Placeholder UI)
                        \Filament\Schemas\Components\Tabs\Tab::make('Backup')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('backup_info')
                                    ->label('Backup Management')
                                    ->content('Coming soon: backup and restore workflow is not connected yet.'),
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
                // Convert arrays to JSON string for storage
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => 'general']
                );
            }

            // Auto-regenerate static favicons when favicon is updated
            if (!empty($data['favicon'])) {
                $this->regenerateFavicons($data['favicon']);
            }

            Notification::make() 
                ->success()
                ->title(__('Saved successfully'))
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * Generate static square favicon PNGs from the uploaded favicon image.
     * Google requires square favicons, minimum 48x48, multiples of 48.
     */
    protected function regenerateFavicons(string $faviconPath): void
    {
        $storagePath = storage_path('app/public/' . $faviconPath);
        if (!file_exists($storagePath)) {
            return;
        }

        $info = @getimagesize($storagePath);
        if (!$info) {
            return;
        }

        $src = match ($info['mime']) {
            'image/png' => @imagecreatefrompng($storagePath),
            'image/jpeg' => @imagecreatefromjpeg($storagePath),
            'image/webp' => @imagecreatefromwebp($storagePath),
            'image/gif' => @imagecreatefromgif($storagePath),
            default => null,
        };

        if (!$src) {
            return;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // Center-crop to square
        $squareSize = min($w, $h);
        $offsetX = (int)(($w - $squareSize) / 2);
        $offsetY = (int)(($h - $squareSize) / 2);

        $sizes = [48, 192];
        foreach ($sizes as $size) {
            $dst = imagecreatetruecolor($size, $size);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, $offsetX, $offsetY, $size, $size, $squareSize, $squareSize);
            imagepng($dst, public_path("favicon-{$size}.png"), 9);
            imagedestroy($dst);
        }

        // Copy 48px as favicon.ico fallback
        @copy(public_path('favicon-48.png'), public_path('favicon.ico'));

        imagedestroy($src);
    }
}
