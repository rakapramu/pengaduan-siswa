<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDashboardModule extends Command
{
    protected $signature = 'make:dashboard {name}';
    protected $description = 'Generate Controller + Views + Route under Dashboard namespace';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $slug = Str::kebab($name); // contoh: Pengaduan -> pengaduan
        $controllerName = "{$name}Controller";
        $controllerNamespace = "App\\Http\\Controllers\\Dashboard";
        $controllerPath = app_path("Http/Controllers/Dashboard/{$controllerName}.php");

        // === 1. Buat Folder Controller Dashboard ===
        if (!File::exists(app_path('Http/Controllers/Dashboard'))) {
            File::makeDirectory(app_path('Http/Controllers/Dashboard'), 0755, true);
        }

        // === 2. Generate Controller ===
        if (!File::exists($controllerPath)) {
            File::put($controllerPath, $this->generateController($name, $slug));
            $this->info("✅ Controller created: {$controllerName}");
        } else {
            $this->warn("⚠️ Controller already exists!");
        }

        // === 3. Buat Folder View ===
        $viewPath = resource_path("views/dashboard/{$slug}");
        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
            $views = ['index', 'create', 'edit', 'show'];
            foreach ($views as $view) {
                File::put("{$viewPath}/{$view}.blade.php", "<h1>{$name} {$view}</h1>");
            }
            $this->info("✅ Views created: resources/views/dashboard/{$slug}/");
        } else {
            $this->warn("⚠️ Views already exist!");
        }

        // === 4. Tambahkan Route ===
        $routeFile = base_path('routes/web.php');
        $routeLine = "\nRoute::resource('{$slug}', {$controllerName}::class);";
        File::append($routeFile, $routeLine);
        $this->info("✅ Route added to web.php");

        $this->info("🎉 Dashboard module {$name} successfully generated!");
    }

    private function generateController($name, $slug)
    {
        return <<<PHP
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    public function index()
    {
        return view('dashboard.{$slug}.index');
    }

    public function create()
    {
        return view('dashboard.{$slug}.create');
    }

    public function store(Request \$request)
    {
        //
    }

    public function show(\$id)
    {
        return view('dashboard.{$slug}.show', compact('id'));
    }

    public function edit(\$id)
    {
        return view('dashboard.{$slug}.edit', compact('id'));
    }

    public function update(Request \$request, \$id)
    {
        //
    }

    public function destroy(\$id)
    {
        //
    }
}
PHP;
    }
}
