<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\GlobalSettings;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            try {
                $currentUrl = $request->path();
                $currentPath = '/' . $currentUrl;

                Log::info('Current path: ' . $currentPath);

                // Récupérer les paramètres globaux
                $settings = GlobalSettings::getInstance();

                // Récupérer les données SEO depuis la base de données
                $seo = SEO::where('url', $currentPath)
                    ->where('model_type', GlobalSettings::class)
                    ->where('model_id', $settings->id)
                    ->first();

                Log::info('SEO data found: ' . ($seo ? 'yes' : 'no'));

                if ($seo) {
                    $seoData = new SEOData(
                        title: $seo->title,
                        description: $seo->description,
                        author: $seo->author,
                        robots: $seo->robots,
                        canonical_url: $seo->canonical_url,
                        image: $seo->image,
                        locale: $settings->locale,
                        site_name: $settings->site_name
                    );
                } else {
                    // Utiliser les paramètres par défaut si aucune donnée SEO n'est trouvée
                    $seoData = new SEOData(
                        title: $settings->site_name,
                        description: $settings->default_description,
                        author: $settings->site_name,
                        robots: 'index, follow',
                        canonical_url: url($currentPath),
                        image: $settings->default_image ? asset('storage/' . $settings->default_image) : null,
                        locale: $settings->locale,
                        site_name: $settings->site_name
                    );
                }

                view()->share('SEOData', $seoData);
                return $next($request);
            } catch (\Exception $e) {
                Log::error('Error in SEO middleware: ' . $e->getMessage());
                return $next($request);
            }
        });
    }
}
