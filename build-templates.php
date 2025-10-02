<?php
/**
 * RatePress Templates Build Script
 *
 * Generates templates.json from individual template config.php files
 * Run with: php build-templates.php
 */

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

                // Build template entry for templates.json
                $templateEntry = [
                    'slug' => $config['slug'],
                    'name' => $config['name'],
                    'description' => $config['description'],
                    'category' => $config['category'],
                    'version' => $config['version'],
                    'author' => $config['author'] ?? 'Unknown',
                    'author_url' => $config['author_url'] ?? '',
                    'tags' => $config['tags'] ?? [],
                    'preview' => $config['preview'] ?? '',
                    'download_url' => $config['download_url'] ?? '',
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
?>