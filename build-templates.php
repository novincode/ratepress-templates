<?php
/**
 * RatePress Templates Build Script
 *
 * Generates templates.json from individual template config.php files
 * Run with: php build-templates.php
 */

require_once 'version-bumper.php';

class TemplatesBuilder
{
    private $templatesDir;
    private $outputFile;

    public function __construct($templatesDir = 'templates', $outputFile = 'templates.json')
    {
        $this->templatesDir = $templatesDir;
        $this->outputFile = $outputFile;
    }

    public function build()
    {
        echo "🔄 Running version bumper...\n";
        $bumper = new VersionBumper($this->templatesDir);
        if ($bumper->isGitRepository()) {
            $bumpedCount = $bumper->bumpVersions();
            if ($bumpedCount > 0) {
                // Commit version changes
                echo "💾 Committing version changes...\n";
                exec("git add templates/*/config.php");
                exec("git commit -m \"Bump template versions based on recent changes\"");
            }
        } else {
            echo "⚠️  Not a git repository, skipping version bumping\n";
        }

        echo "🔍 Scanning templates directory...\n";

        $templates = [];
        $templateDirs = glob($this->templatesDir . '/*/*', GLOB_ONLYDIR);

        foreach ($templateDirs as $templateDir) {
            $configFile = $templateDir . '/config.php';

            if (!file_exists($configFile)) {
                echo "⚠️  Skipping $templateDir - no config.php found\n";
                continue;
            }

            try {
                $config = include $configFile;

                if (!is_array($config) || !isset($config['slug'])) {
                    echo "⚠️  Skipping $templateDir - invalid config.php\n";
                    continue;
                }

                // Validate required fields
                $requiredFields = ['slug', 'name', 'description', 'category', 'version'];
                $missingFields = array_diff($requiredFields, array_keys($config));

                if (!empty($missingFields)) {
                    echo "⚠️  Skipping {$config['slug']} - missing required fields: " . implode(', ', $missingFields) . "\n";
                    continue;
                }

                // Generate URLs automatically
                $slug = $config['slug'];
                $baseUrl = 'https://raw.githubusercontent.com/novincode/ratepress-templates/main/templates/' . $slug;
                $downloadUrl = 'https://github.com/novincode/ratepress-templates/tree/main/templates/' . $slug;

                // Check for preview image or generate one
                $previewPath = $templateDir . '/preview.png';
                if (file_exists($previewPath)) {
                    $previewUrl = $baseUrl . '/preview.png';
                    echo "📸 Using existing preview for {$config['slug']}\n";
                } else {
                    echo "🎨 Generating preview for {$config['slug']}...\n";
                    $previewUrl = $this->generatePreview($templateDir, $config);
                }

                // Build template entry for templates.json
                $templateEntry = [
                    'slug' => $slug,
                    'name' => $config['name'],
                    'description' => $config['description'],
                    'category' => $config['category'],
                    'version' => $config['version'],
                    'author' => $config['author'] ?? 'Unknown',
                    'author_uri' => $config['author_url'] ?? '',
                    'tags' => $config['tags'] ?? [],
                    'preview_image' => $previewUrl,
                    'download_url' => $downloadUrl,
                    'min_ratepress_version' => $config['min_ratepress_version'] ?? '1.0.0',
                    'requires_core_js' => $config['requires_core_js'] ?? false,
                ];

                $templates[] = $templateEntry;
                echo "✅ Added {$config['slug']}\n";

            } catch (Exception $e) {
                echo "❌ Error processing $templateDir: " . $e->getMessage() . "\n";
            }
        }

        // Sort templates by slug for consistent output
        usort($templates, function($a, $b) {
            return strcmp($a['slug'], $b['slug']);
        });

        // Write templates.json
        $json = json_encode($templates, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (file_put_contents($this->outputFile, $json)) {
            echo "\n🎉 Successfully generated {$this->outputFile} with " . count($templates) . " templates\n";
            return true;
        } else {
            echo "\n❌ Failed to write {$this->outputFile}\n";
            return false;
        }
    }

    private function generatePreview($templateDir, $config)
    {
        $slug = $config['slug'];
        $previewPath = $templateDir . '/preview.png';

        // Create a temporary HTML file for preview generation
        $htmlContent = $this->createPreviewHtml($templateDir, $config);
        $tempHtml = sys_get_temp_dir() . '/' . str_replace('/', '_', $slug) . '_preview.html';

        if (file_put_contents($tempHtml, $htmlContent) === false) {
            echo "❌ Failed to create temporary HTML file\n";
            return 'https://via.placeholder.com/320x200/6366f1/ffffff?text=' . urlencode($config['name']);
        }

        // Check if Node.js is available
        $nodeAvailable = shell_exec('which node') !== null;
        if (!$nodeAvailable) {
            echo "⚠️  Node.js not available, skipping preview generation\n";
            unlink($tempHtml);
            return 'https://via.placeholder.com/320x200/6366f1/ffffff?text=' . urlencode($config['name']);
        }

        // Run Node.js script to generate preview
        $nodeScript = __DIR__ . '/generate-preview.js';
        $command = "node \"$nodeScript\" \"$tempHtml\" \"$previewPath\" 2>&1";

        exec($command, $output, $returnCode);

        // Clean up temp file
        unlink($tempHtml);

        if ($returnCode === 0 && file_exists($previewPath)) {
            echo "✅ Generated preview for {$config['slug']}\n";
            return "https://raw.githubusercontent.com/novincode/ratepress-templates/main/templates/{$slug}/preview.png";
        } else {
            echo "❌ Failed to generate preview: " . implode("\n", $output) . "\n";
            return 'https://via.placeholder.com/320x200/6366f1/ffffff?text=' . urlencode($config['name']);
        }
    }

    private function createPreviewHtml($templateDir, $config)
    {
        // Read style.css if it exists
        $styleContent = '';
        $stylePath = $templateDir . '/style.css';
        if (file_exists($stylePath)) {
            $styleContent = file_get_contents($stylePath);
        }

        // Generate template HTML using the actual render.php
        $templateHtml = $this->renderTemplatePreview($templateDir, $config);

        // Get preview background from config or use default dark
        $previewBg = $config['preview_background'] ?? '#1a1a1a';

        // Get zoom from config or use default 1
        $zoom = $config['preview_zoom'] ?? 1;

        // Get preview theme from config or use default light
        $previewTheme = $config['preview_theme'] ?? 'light';
        $themeAttribute = $previewTheme === 'dark' ? ' data-theme="dark"' : '';

        // Create a simple HTML preview - just the component on colored background
        $html = '<!DOCTYPE html>
<html lang="en"' . $themeAttribute . '>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($config['name']) . ' Preview</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: ' . $previewBg . ';
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100vw;
        }

        .preview-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            transform: scale(' . $zoom . ');
            transform-origin: center;
        }

        /* Template specific styles */
        ' . $styleContent . '
    </style>
</head>
<body>
    <div class="preview-wrapper">
        ' . $templateHtml . '
    </div>
</body>
</html>';

        return $html;
    }

    private function renderTemplatePreview($templateDir, $config)
    {
        $renderPath = $templateDir . '/render.php';

        if (!file_exists($renderPath)) {
            return $this->createFallbackPreview($config);
        }

        // Create template data from config demo_data
        $demoData = $config['demo_data'] ?? [];
        $demoData['theme'] = $config['preview_theme'] ?? 'light';
        $template_data = (object) $demoData;

        // Add additional required data
        $template_data->object_id = 1;
        $template_data->object_type = 'post';
        $template_data->size = 'medium';
        $template_data->is_js_mode = false;
        $template_data->theme = $config['preview_theme'] ?? 'light';

        // Extract settings defaults
        $settings = $config['settings'] ?? [];
        foreach ($settings as $settingKey => $settingConfig) {
            if (isset($settingConfig['default'])) {
                $template_data->$settingKey = $settingConfig['default'];
            }
        }

        // Define helper functions that render.php might need
        if (!function_exists('esc_attr')) {
            function esc_attr($text) {
                return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        }
        if (!function_exists('_e')) {
            function _e($text) {
                echo $text;
            }
        }
        if (!function_exists('__')) {
            function __($text) {
                return $text;
            }
        }
        if (!function_exists('_n')) {
            function _n($single, $plural, $number) {
                return $number === 1 ? $single : $plural;
            }
        }

        // Capture output from render.php
        ob_start();
        try {
            include $renderPath;
        } catch (\Exception $e) {
            ob_end_clean();
            return $this->createFallbackPreview($config);
        }
        $html = ob_get_clean();

        return $html;
    }

    private function createFallbackPreview($config)
    {
        $category = $config['category'];
        $slug = $config['slug'];

        // Create a demo based on category
        switch ($category) {
            case 'binary':
                return '<div class="ratepress-widget ratepress-' . str_replace('/', '-', $slug) . '" data-size="medium">
                    <button class="ratepress-heart-btn active" type="button">
                        <svg class="ratepress-heart-icon" viewBox="0 0 24 24" fill="none">
                            <path class="heart-outline" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke="currentColor" stroke-width="1.5"/>
                            <path class="heart-fill" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="currentColor"/>
                        </svg>
                        <span class="ratepress-heart-count">42</span>
                    </button>
                </div>';

            case 'bipolar':
                return '<div class="ratepress-widget ratepress-favorite-widget" data-size="medium">
                    <button class="ratepress-favorite-btn active" type="button">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>';

            case 'scale':
            default:
                return '<div class="ratepress-widget ratepress-stars-widget" data-size="medium">
                    <div class="ratepress-stars">
                        ' . str_repeat('<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>', 5) . '
                    </div>
                    <span class="ratepress-rating-text">4.5 out of 5</span>
                </div>';
        }
    }
}

// Run the build
if ($argc > 1 && $argv[1] === '--help') {
    echo "RatePress Templates Builder\n\n";
    echo "Usage: php build-templates.php [templates-dir] [output-file]\n\n";
    echo "Arguments:\n";
    echo "  templates-dir  Directory containing templates (default: templates)\n";
    echo "  output-file    Output JSON file (default: templates.json)\n\n";
    echo "Examples:\n";
    echo "  php build-templates.php\n";
    echo "  php build-templates.php templates templates.json\n";
    exit(0);
}

$templatesDir = $argc > 1 ? $argv[1] : 'templates';
$outputFile = $argc > 2 ? $argv[2] : 'templates.json';

$builder = new TemplatesBuilder($templatesDir, $outputFile);
$builder->build();