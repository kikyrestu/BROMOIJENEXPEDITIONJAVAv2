<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Package;

class WhatsAppLinkGenerator
{
    protected static string $baseUrl = 'https://wa.me/';
    protected static string $defaultPhone = '6281234567890'; // Replace with env config in real app
    protected static ?string $cachedPhone = null;

    protected static function resolvePhone(): string
    {
        if (self::$cachedPhone !== null) {
            return self::$cachedPhone;
        }

        $providerPhone = Setting::where('key', 'provider_phone')->value('value');
        $overrideWhatsapp = Setting::where('key', 'whatsapp_number')->value('value');

        $phone = $overrideWhatsapp ?: ($providerPhone ?: self::$defaultPhone);
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        self::$cachedPhone = $phone ?: self::$defaultPhone;

        return self::$cachedPhone;
    }

    public static function generate(Package $package, string $source = 'website'): string
    {
        $phone = self::resolvePhone();
        
        // Base message or custom template
        $message = $package->wa_template_message ?? "Hi, I am interested in the *{name}* package.\nLink: {url}\nSource: {source}";

        // Replacements
        $replacements = [
            '{name}' => $package->name,
            '{url}' => url("/packages/{$package->slug}"),
            '{source}' => $source,
        ];

        $finalMessage = str_replace(array_keys($replacements), array_values($replacements), $message);

        return self::$baseUrl . $phone . '?text=' . urlencode($finalMessage);
    }
}
