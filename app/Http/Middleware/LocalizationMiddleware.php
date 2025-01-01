<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Accept-Language')) {
//            app()->setLocale($request->header('Accept-Language'));
            $locale = $this->extractLocaleFromHeader($request->header('Accept-Language'));

            // Set the application locale
            app()->setLocale($locale);
        } elseif (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }

    protected function extractLocaleFromHeader(string $acceptLanguage): string
    {
        // Split the header into individual locales
        $locales = explode(',', $acceptLanguage);

        // Extract the first locale (e.g., "en_GB;q=0.9" -> "en_GB")
        $primaryLocale = trim(explode(';', $locales[0])[0]);

        // Validate the locale using a simple regex
        if ($this->isValidLocale($primaryLocale)) {
            return $primaryLocale;
        }

        // Fallback to the default locale if the extracted locale is invalid
        return config('app.fallback_locale', 'en');
    }

    /**
     * Validate the locale using a simple regex.
     *
     * @param string $locale
     * @return bool
     */
    protected function isValidLocale(string $locale): bool
    {
        // Basic regex to validate locale format (e.g., "en", "en_GB", "en-GB")
        return preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $locale);
    }

}
