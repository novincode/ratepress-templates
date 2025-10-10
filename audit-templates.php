<?php
/**
 * Template Audit Script
 * Scans all templates and reports missing features
 */

$templatesDir = __DIR__ . '/templates';
$issues = [];

function scanTemplates($dir) {
    global $issues;
    $templates = scandir($dir);
    foreach ($templates as $template) {
        if ($template === '.' || $template === '..') continue;
        $templatePath = $dir . '/' . $template;
        if (is_dir($templatePath)) {
            scanTemplate($templatePath);
        }
    }
}

function scanTemplate($templatePath) {
    global $issues;
    $templateName = basename($templatePath);
    $configFile = $templatePath . '/config.php';
    $renderFile = $templatePath . '/render.php';
    $styleFile = $templatePath . '/style.css';

    $issues[$templateName] = [];

    // Check config.php for show_counts
    if (file_exists($configFile)) {
        $config = include $configFile;
        $hasShowCounts = isset($config['settings']['show_counts']);
        if (!$hasShowCounts) {
            $issues[$templateName][] = 'Missing show_counts setting in config.php';
        }
    }

    // Check render.php for conditional count display
    if (file_exists($renderFile)) {
        $renderContent = file_get_contents($renderFile);
        // Look for data-count or spans with counts not wrapped in show_counts condition
        if (preg_match('/data-count="[^"]*"/', $renderContent) && !preg_match('/if\s*\(\s*\$show_counts\s*\)/', $renderContent)) {
            $issues[$templateName][] = 'Count display not conditional on show_counts in render.php';
        }
    }

    // Check style.css for dark mode
    if (file_exists($styleFile)) {
        $styleContent = file_get_contents($styleFile);
        if (!preg_match('/\[data-theme="dark"\]/', $styleContent)) {
            $issues[$templateName][] = 'Missing dark mode CSS variables in style.css';
        }
    }
}

scanTemplates($templatesDir);

// Output results
foreach ($issues as $template => $templateIssues) {
    if (!empty($templateIssues)) {
        echo "Template: $template\n";
        foreach ($templateIssues as $issue) {
            echo "  - $issue\n";
        }
        echo "\n";
    }
}

if (empty(array_filter($issues))) {
    echo "All templates are compliant!\n";
}
?>