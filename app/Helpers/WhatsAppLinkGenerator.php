<?php

namespace App\Helpers;

use App\Models\Package;

class WhatsAppLinkGenerator
{
    protected static string $baseUrl = 'https://wa.me/';
    protected static string $defaultPhone = '6281234567890'; // Replace with env config in real app

    public static function generate(Package $package, string $source = 'website'): string
    {
        $phone = config('settings.whatsapp_number', self::$defaultPhone);
        
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
