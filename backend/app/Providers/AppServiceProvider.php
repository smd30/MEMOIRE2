<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration pour PostgreSQL
        Schema::defaultStringLength(191);

        // Directives Blade personnalisées
        $this->registerBladeDirectives();

        // Composants Blade personnalisés
        $this->registerBladeComponents();
    }

    /**
     * Enregistrer les directives Blade personnalisées
     */
    protected function registerBladeDirectives(): void
    {
        // Directive pour vérifier les rôles
        Blade::directive('role', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$expression})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // Directive pour vérifier les permissions
        Blade::directive('can', function ($expression) {
            return "<?php if(auth()->check() && Gate::allows({$expression})): ?>";
        });

        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });

        // Directive pour afficher les montants en euros
        Blade::directive('money', function ($expression) {
            return "<?php echo number_format({$expression}, 2, ',', ' ') . ' €'; ?>";
        });

        // Directive pour formater les dates
        Blade::directive('date', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse({$expression})->format('d/m/Y'); ?>";
        });

        // Directive pour formater les dates et heures
        Blade::directive('datetime', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse({$expression})->format('d/m/Y H:i'); ?>";
        });
    }

    /**
     * Enregistrer les composants Blade personnalisés
     */
    protected function registerBladeComponents(): void
    {
        // Composant pour les alertes
        Blade::component('components.alert', 'alert');

        // Composant pour les boutons
        Blade::component('components.button', 'button');

        // Composant pour les cartes
        Blade::component('components.card', 'card');

        // Composant pour les formulaires
        Blade::component('components.form', 'form');

        // Composant pour les tableaux
        Blade::component('components.table', 'table');

        // Composant pour la pagination
        Blade::component('components.pagination', 'pagination');
    }
}
