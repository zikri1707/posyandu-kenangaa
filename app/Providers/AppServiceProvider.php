<?php

namespace App\Providers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Policies\ActivityLogPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\MedicalRecordPolicy;
use App\Policies\PatientPolicy;
use App\Policies\ReportPolicy;
use App\Policies\SchedulePolicy;
use App\View\Components\Layouts\UI\Alert;
use App\View\Components\Layouts\UI\Breadcrumbs;
use App\View\Components\Layouts\UI\Button;
use App\View\Components\Layouts\UI\Card;
use App\View\Components\Layouts\UI\DataCard;
use App\View\Components\Layouts\UI\Footer;
use App\View\Components\Layouts\UI\Modal;
use App\View\Components\Layouts\UI\Navbar;
use App\View\Components\Layouts\UI\Pagination;
use App\View\Components\Layouts\UI\ProgressBar;
use App\View\Components\Layouts\UI\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Patient::class => PatientPolicy::class,
        MedicalRecord::class => MedicalRecordPolicy::class,
        \App\Models\Schedule::class => SchedulePolicy::class,
        \App\Models\Article::class => ArticlePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\Illuminate\Foundation\Vite::class, function () {
            return new \App\Support\CustomVite;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Superadmin Permission Bypass ─────────────────────────────
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        if (config('app.env') !== 'local' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register model policies
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(MedicalRecord::class, MedicalRecordPolicy::class);
        Gate::policy(\App\Models\Schedule::class, SchedulePolicy::class);
        Gate::policy(\App\Models\Article::class, ArticlePolicy::class);

        // Register ability-based policies for non-model resources
        Gate::define('viewActivityLogs', [ActivityLogPolicy::class, 'viewAny']);
        Gate::define('deleteActivityLogs', [ActivityLogPolicy::class, 'delete']);
        Gate::define('viewReports', [ReportPolicy::class, 'viewAny']);
        Gate::define('exportReports', [ReportPolicy::class, 'export']);

        // ── UI Component Aliases ──────────────────────────────────────
        Blade::component(Button::class, 'button');
        Blade::component(Card::class, 'card');
        Blade::component(Table::class, 'table');
        Blade::component(Breadcrumbs::class, 'breadcrumb');
        Blade::component(Pagination::class, 'pagination-ui');
        Blade::component(Navbar::class, 'ui-navbar');
        Blade::component(Footer::class, 'ui-footer');
        Blade::component(ProgressBar::class, 'progress-bar');

        // Class-based registration for consistency
        Blade::component(Alert::class, 'alert');
        Blade::component(Modal::class, 'modal');
        Blade::component(DataCard::class, 'datacard');

        // ── Date-Time Component Aliases ───────────────────────────────
        Blade::component('components.date-time.datepicker', 'datepicker');
        Blade::component('components.date-time.datetime-picker', 'datetime-picker');

        // ── Modal Component Aliases ───────────────────────────────────
        Blade::component('components.modals.confirm-modal', 'confirm-modal');
        Blade::component('components.modals.form-modal', 'form-modal');
        Blade::component('components.modals.info-modal', 'info-modal');

        // ── Widget Component Aliases ──────────────────────────────────
        Blade::component('components.widget.stats-card', 'widget.stats-card');
        Blade::component('components.widget.dashboard-card', 'widget.dashboard-card');
        Blade::component('components.widget.chart-widget', 'widget.chart-widget');
    }
}
