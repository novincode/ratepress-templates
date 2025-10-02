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

        // Check if pnpm is available, if not try to install it
        $pnpmAvailable = shell_exec('which pnpm') !== null;
        if (!$pnpmAvailable) {
            echo "📦 pnpm not found, attempting to install...\n";
            // Try to install pnpm globally using npm
            $installPnpmCmd = "npm install -g pnpm --silent 2>&1";
            exec($installPnpmCmd, $installPnpmOutput, $installPnpmCode);
            
            if ($installPnpmCode !== 0) {
                echo "⚠️  Failed to install pnpm via npm, trying alternative method...\n";
                // Try alternative installation method (curl)
                $altInstallCmd = "curl -fsSL https://get.pnpm.io/install.sh | sh - 2>&1";
                exec($altInstallCmd, $altInstallOutput, $altInstallCode);
                
                if ($altInstallCode !== 0) {
                    echo "⚠️  Failed to install pnpm, falling back to npm for Puppeteer installation\n";
                    $useNpmFallback = true;
                } else {
                    // Reload PATH to include newly installed pnpm
                    $newPath = shell_exec('source ~/.bashrc 2>/dev/null; source ~/.zshrc 2>/dev/null; echo $PATH');
                    if ($newPath) {
                        putenv("PATH=$newPath");
                    }
                    $pnpmAvailable = shell_exec('which pnpm') !== null;
                }
            } else {
                $pnpmAvailable = shell_exec('which pnpm') !== null;
            }
        }

        // Check if Playwright is installed, if not install it
        $playwrightPath = __DIR__ . '/node_modules/@playwright';
        if (!file_exists($playwrightPath)) {
            echo "📦 Installing Playwright (one-time setup)...\n";
            $packageManager = $pnpmAvailable ? 'pnpm' : 'npm';
            $installCmd = "cd " . escapeshellarg(__DIR__) . " && $packageManager install --silent 2>&1";
            exec($installCmd, $installOutput, $installCode);
            
            if ($installCode !== 0 || !file_exists($playwrightPath)) {
                echo "⚠️  Failed to install Playwright, skipping preview generation\n";
                unlink($tempHtml);
                return 'https://via.placeholder.com/320x200/6366f1/ffffff?text=' . urlencode($config['name']);
            }

            // Install Playwright browsers
            echo "📦 Installing Playwright browsers...\n";
            $installBrowsersCmd = "cd " . escapeshellarg(__DIR__) . " && npx playwright install chromium --silent 2>&1";
            exec($installBrowsersCmd, $browsersOutput, $browsersCode);
            
            if ($browsersCode !== 0) {
                echo "⚠️  Failed to install Playwright browsers, skipping preview generation\n";
                unlink($tempHtml);
                return 'https://via.placeholder.com/320x200/6366f1/ffffff?text=' . urlencode($config['name']);
            }
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

    private function createPreviewImage($templateDir, $config, $outputPath)
    {
        // Check if GD library is available
        if (!extension_loaded('gd')) {
            echo "⚠️  GD library not available, skipping image generation\n";
            return false;
        }

        // Create image
        $width = 640;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);

        // Enable alpha blending
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Create gradient background (purple to blue)
        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / $height;
            $r = (int)(102 + (118 - 102) * $ratio);
            $g = (int)(126 + (74 - 126) * $ratio);
            $b = (int)(234 + (162 - 234) * $ratio);
            $color = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, 0, $y, $width, $y + 1, $color);
        }

        // Create semi-transparent white card
        $cardX = 120;
        $cardY = 80;
        $cardWidth = 400;
        $cardHeight = 240;
        
        // Draw card shadow
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 90);
        imagefilledrectangle($image, $cardX + 5, $cardY + 5, $cardX + $cardWidth + 5, $cardY + $cardHeight + 5, $shadowColor);
        
        // Draw card background
        $cardBg = imagecolorallocatealpha($image, 255, 255, 255, 10);
        imagefilledrectangle($image, $cardX, $cardY, $cardX + $cardWidth, $cardY + $cardHeight, $cardBg);

        // Add template name
        $textColor = imagecolorallocate($image, 31, 41, 55);
        $name = $this->wrapText($config['name'], 30);
        $this->imageTextCenter($image, $name, $width / 2, 120, $textColor, 5);

        // Add description
        $descColor = imagecolorallocate($image, 107, 114, 128);
        $desc = $this->wrapText($config['description'], 40);
        $this->imageTextCenter($image, $desc, $width / 2, 160, $descColor, 3);

        // Add category badge
        $badgeColor = imagecolorallocate($image, 99, 102, 241);
        $badgeText = strtoupper($config['category']);
        $this->imageTextCenter($image, $badgeText, $width / 2, 220, $badgeColor, 4);

        // Add template icon based on category
        $this->drawTemplateIcon($image, $config['category'], $width / 2, 270);

        // Add version at bottom
        $versionText = 'v' . $config['version'];
        $versionColor = imagecolorallocate($image, 156, 163, 175);
        $this->imageTextCenter($image, $versionText, $width / 2, 340, $versionColor, 2);

        // Save image
        $result = imagepng($image, $outputPath, 9);
        imagedestroy($image);

        return $result;
    }

    private function drawTemplateIcon($image, $category, $x, $y)
    {
        $color = imagecolorallocate($image, 99, 102, 241);
        
        switch ($category) {
            case 'binary':
                // Draw heart
                $this->drawHeart($image, $x, $y, 30, $color);
                break;
            case 'bipolar':
                // Draw thumbs up/down
                $this->drawThumbsUpDown($image, $x, $y, 30, $color);
                break;
            case 'scale':
            default:
                // Draw stars
                $this->drawStars($image, $x, $y, 20, $color);
                break;
        }
    }

    private function drawHeart($image, $x, $y, $size, $color)
    {
        // Simple heart shape using circles and triangle
        imagefilledellipse($image, $x - $size/3, $y - $size/4, $size/2, $size/2, $color);
        imagefilledellipse($image, $x + $size/3, $y - $size/4, $size/2, $size/2, $color);
        
        $points = [
            $x - $size/2, $y - $size/6,
            $x, $y + $size/2,
            $x + $size/2, $y - $size/6
        ];
        imagefilledpolygon($image, $points, 3, $color);
    }

    private function drawStars($image, $x, $y, $size, $color)
    {
        // Draw 5 small stars
        for ($i = 0; $i < 5; $i++) {
            $starX = $x - 50 + ($i * 25);
            $this->drawStar($image, $starX, $y, $size/2, $color);
        }
    }

    private function drawStar($image, $x, $y, $size, $color)
    {
        $points = [];
        for ($i = 0; $i < 10; $i++) {
            $angle = ($i * 36 - 90) * M_PI / 180;
            $r = ($i % 2 == 0) ? $size : $size / 2;
            $points[] = $x + $r * cos($angle);
            $points[] = $y + $r * sin($angle);
        }
        imagefilledpolygon($image, $points, 5, $color);
    }

    private function drawThumbsUpDown($image, $x, $y, $size, $color)
    {
        // Draw simple thumbs up
        imagefilledrectangle($image, $x - 15, $y - 10, $x - 5, $y + 20, $color);
        imagefilledrectangle($image, $x - 20, $y - 20, $x, $y - 10, $color);
        
        // Draw thumbs down
        imagefilledrectangle($image, $x + 5, $y - 10, $x + 15, $y + 20, $color);
        imagefilledrectangle($image, $x, $y + 10, $x + 20, $y + 20, $color);
    }

    private function wrapText($text, $maxLength)
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        return substr($text, 0, $maxLength - 3) . '...';
    }

    private function imageTextCenter($image, $text, $x, $y, $color, $fontSize)
    {
        // Calculate text width (rough estimation)
        $textWidth = strlen($text) * $fontSize * 1.5;
        $startX = $x - ($textWidth / 2);
        
        imagestring($image, $fontSize, (int)$startX, $y, $text, $color);
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
?>
