<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use RalphJSmit\Laravel\SEO\Models\SEO;
use Illuminate\Support\Str;
use App\Models\GlobalSettings;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page des paramètres
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = GlobalSettings::getInstance();

        // Récupérer les données SEO pour chaque page
        $pagesSeo = [];
        $validPages = ['home', 'contact', 'offres', 'client', 'portfolio'];

        foreach ($validPages as $page) {
            $url = match ($page) {
                'home' => '/',
                'offres' => '/NosOffres',
                'contact' => '/Contact',
                'client' => '/Client',
                'portfolio' => '/Portfolio',
                default => '/'
            };

            $pagesSeo[$page] = SEO::where('url', $url)
                ->where('model_type', GlobalSettings::class)
                ->where('model_id', $settings->id)
                ->first();
        }

        return view('admin.settings', [
            'settings' => $settings,
            'pagesSeo' => $pagesSeo
        ]);
    }

    /**
     * Met à jour les paramètres du compte utilisateur
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
            }
        }

        // Mettre à jour les informations de l'utilisateur
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.settings.index')->with('success', 'Vos informations ont été mises à jour avec succès.');
    }

    /**
     * Met à jour les paramètres SEO généraux du site
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'default_description' => 'nullable|string',
            'default_keywords' => 'nullable|string',
            'default_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'locale' => 'required|string|in:fr_FR,en_US,en_GB',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);

        try {
            // Mettre à jour le nom de l'application
            $this->updateEnvVariable('APP_NAME', '"' . $validated['site_name'] . '"');

            // Récupérer les paramètres existants
            $settings = GlobalSettings::getInstance();
            $currentImagePath = $settings->default_image;

            // Gérer l'upload de l'image par défaut
            if ($request->hasFile('default_image')) {
                // Supprimer l'ancienne image si elle existe
                if ($currentImagePath && Storage::disk('public')->exists($currentImagePath)) {
                    Storage::disk('public')->delete($currentImagePath);
                }

                // Stocker la nouvelle image
                $currentImagePath = $request->file('default_image')->store('seo', 'public');
            }

            // Mettre à jour ou créer les paramètres globaux
            $settings->fill([
                'site_name' => $validated['site_name'],
                'default_description' => $validated['default_description'],
                'default_keywords' => $validated['default_keywords'],
                'default_image' => $currentImagePath,
                'locale' => $validated['locale'],
                'social_facebook' => $validated['social_facebook'],
                'social_twitter' => $validated['social_twitter'],
                'social_instagram' => $validated['social_instagram'],
                'social_linkedin' => $validated['social_linkedin'],
            ]);
            $settings->save();

            // Préparer les données SEO
            $seoData = [
                'title' => $validated['site_name'],
                'description' => $validated['default_description'],
                'keywords' => $validated['default_keywords'],
                'image' => $currentImagePath ? asset('storage/' . $currentImagePath) : ($settings->default_image ? asset('storage/' . $settings->default_image) : null),
                'locale' => $validated['locale'],
                'site_name' => $validated['site_name'],
                'author' => config('app.name'),
                'robots' => 'index, follow',
                'canonical_url' => url('/'),
            ];

            // Mettre à jour les métadonnées SEO
            $seo = $settings->seo()->updateOrCreate(
                ['url' => '/'],
                $seoData
            );

            // Mettre à jour la configuration
            config(['seo.site_name' => $validated['site_name']]);
            config(['seo.default_description' => $validated['default_description']]);
            config(['seo.default_keywords' => $validated['default_keywords']]);
            config(['seo.default_image' => $currentImagePath ? asset('storage/' . $currentImagePath) : ($settings->default_image ? asset('storage/' . $settings->default_image) : null)]);
            config(['seo.locale' => $validated['locale']]);
            config(['seo.social_facebook' => $validated['social_facebook']]);
            config(['seo.social_twitter' => $validated['social_twitter']]);
            config(['seo.social_instagram' => $validated['social_instagram']]);
            config(['seo.social_linkedin' => $validated['social_linkedin']]);

            // Vider le cache de configuration
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect()->route('admin.settings.index')->with('success', 'Les paramètres SEO ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres SEO: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des paramètres SEO.'])->withInput();
        }
    }

    /**
     * Met à jour les paramètres SEO spécifiques à une page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePageSeo(Request $request, $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            // Vérifier que la page est valide
            $validPages = ['home', 'contact', 'offres', 'client', 'portfolio'];
            if (!in_array($page, $validPages)) {
                return back()->withErrors(['error' => 'Page invalide.']);
            }

            // Déterminer l'URL de la page
            $url = match ($page) {
                'home' => '/',
                'offres' => '/NosOffres',
                'contact' => '/Contact',
                'client' => '/Client',
                'portfolio' => '/Portfolio',
                default => '/'
            };

            // Mettre à jour les paramètres globaux avec les métadonnées de la page
            $settings = GlobalSettings::getInstance();
            $settings->seo()->updateOrCreate(
                ['url' => $url],
                [
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                ]
            );

            return redirect()->route('admin.settings.index')->with('success', 'Les paramètres SEO de la page ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres SEO de la page: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des paramètres SEO de la page.'])->withInput();
        }
    }

    /**
     * Met à jour une variable d'environnement dans le fichier .env
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    private function updateEnvVariable($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            // Si la clé existe déjà, la remplacer
            if (strpos($content, "{$key}=") !== false) {
                $content = preg_replace("/{$key}=(.*)\n/", "{$key}={$value}\n", $content);
            } else {
                // Sinon, l'ajouter à la fin du fichier
                $content .= "{$key}={$value}\n";
            }

            file_put_contents($path, $content);
        }

        // Effacer le cache de configuration
        Artisan::call('config:clear');
    }

    /**
     * Met à jour les préférences de l'application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'theme' => 'required|string|in:light,dark,auto',
            'language' => 'required|string|in:fr,en',
            'time_format' => 'required|string|in:12h,24h',
            'email_notifications' => 'sometimes|boolean',
            'browser_notifications' => 'sometimes|boolean',
        ]);

        // Récupérer l'utilisateur
        $user = Auth::user();

        // Stocker les préférences dans un champ JSON
        $user->preferences = [
            'theme' => $validated['theme'],
            'language' => $validated['language'],
            'time_format' => $validated['time_format'],
            'notifications' => [
                'email' => $request->has('email_notifications'),
                'browser' => $request->has('browser_notifications'),
            ]
        ];

        $user->save();

        return redirect()->route('admin.settings.index')->with('success', 'Préférences mises à jour avec succès.');
    }

    /**
     * Met à jour les paramètres SEO spécifiques à une page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSeoPage(Request $request, $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            Log::info('Début de la mise à jour SEO pour la page : ' . $page);
            Log::info('Données validées :', $validated);

            // Vérifier que la page est valide
            $validPages = ['home', 'contact', 'offres', 'client', 'portfolio'];
            if (!in_array($page, $validPages)) {
                Log::error('Page invalide : ' . $page);
                return back()->withErrors(['error' => 'Page invalide.']);
            }

            // Récupérer les paramètres globaux
            $settings = GlobalSettings::getInstance();
            Log::info('ID des paramètres globaux : ' . $settings->id);

            // Déterminer l'URL de la page
            $url = match ($page) {
                'home' => '/',
                'offres' => '/NosOffres',
                'contact' => '/Contact',
                'client' => '/Client',
                'portfolio' => '/Portfolio',
                default => '/'
            };
            Log::info('URL de la page : ' . $url);

            // Vérifier si une entrée SEO existe déjà
            $existingSeo = SEO::where('url', $url)
                ->where('model_type', GlobalSettings::class)
                ->where('model_id', $settings->id)
                ->first();

            Log::info('Entrée SEO existante : ' . ($existingSeo ? 'Oui (ID: ' . $existingSeo->id . ')' : 'Non'));

            // Préparer les données SEO
            $seoData = [
                'url' => $url,
                'model_type' => GlobalSettings::class,
                'model_id' => $settings->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'author' => $settings->site_name,
                'robots' => 'index, follow',
                'canonical_url' => url($url),
                'image' => $settings->default_image ? asset('storage/' . $settings->default_image) : null,
                'locale' => $settings->locale,
                'site_name' => $settings->site_name,
                'keywords' => $settings->default_keywords
            ];

            Log::info('Données SEO à enregistrer :', $seoData);

            // Mettre à jour ou créer les données SEO pour la page
            $seo = SEO::updateOrCreate(
                [
                    'url' => $url,
                    'model_type' => GlobalSettings::class,
                    'model_id' => $settings->id
                ],
                $seoData
            );

            Log::info('SEO mis à jour/créé avec succès. ID : ' . $seo->id);

            // Vérifier que les données ont bien été enregistrées
            $verification = SEO::find($seo->id);
            Log::info('Vérification des données enregistrées :', $verification->toArray());

            return redirect()->route('admin.settings.index')->with('success', 'Les paramètres SEO de la page ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres SEO de la page: ' . $e->getMessage());
            Log::error('Stack trace : ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour des paramètres SEO de la page : ' . $e->getMessage()])->withInput();
        }
    }
}
