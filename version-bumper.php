<?php
/**
 * RatePress Templates Version Bumper
 *
 * Automatically bumps version numbers in template config.php files
 * based on git changes since last commit.
 */

class VersionBumper
{
    private $templatesDir;

    public function __construct($templatesDir = 'templates')
    {
        $this->templatesDir = $templatesDir;
    }

    /**
     * Bump versions for all templates that have changes
     */
    public function bumpVersions()
    {
        echo "🔄 Checking for template changes to bump versions...\n";

        $templateDirs = glob($this->templatesDir . '/*/*', GLOB_ONLYDIR);
        $bumpedCount = 0;

        foreach ($templateDirs as $templateDir) {
            $configFile = $templateDir . '/config.php';

            if (!file_exists($configFile)) {
                continue;
            }

            $slug = basename(dirname($templateDir)) . '/' . basename($templateDir);

            // Check if template files have changes
            $changedFiles = $this->getChangedFiles($templateDir);

            if (empty($changedFiles)) {
                echo "⏭️  No changes in {$slug}\n";
                continue;
            }

            // Determine change type
            $changeType = $this->determineChangeType($changedFiles);

            // Bump version
            $newVersion = $this->bumpVersion($configFile, $changeType);

            if ($newVersion) {
                echo "⬆️  Bumped {$slug} to {$newVersion} ({$changeType} change)\n";
                $bumpedCount++;
            }
        }

        echo "\n✅ Bumped versions for {$bumpedCount} template(s)\n";
        return $bumpedCount;
    }

    /**
     * Get list of changed files in a template directory
     */
    private function getChangedFiles($templateDir)
    {
        $changedFiles = [];

        // Files to check for changes
        $checkFiles = ['config.php', 'render.php', 'style.css', 'script.js'];

        foreach ($checkFiles as $file) {
            $filePath = $templateDir . '/' . $file;

            if (!file_exists($filePath)) {
                continue;
            }

            // Use git diff to check if file has changes
            $output = [];
            $returnCode = 0;
            exec("git diff HEAD -- \"{$filePath}\"", $output, $returnCode);

            if ($returnCode === 0 && !empty($output)) {
                $changedFiles[$file] = $output;
            }
        }

        return $changedFiles;
    }

    /**
     * Determine if changes are 'minor' or 'major'
     */
    private function determineChangeType($changedFiles)
    {
        $totalLinesChanged = 0;

        foreach ($changedFiles as $file => $diff) {
            // Count added/removed lines (excluding context lines)
            $addedLines = 0;
            $removedLines = 0;

            foreach ($diff as $line) {
                if (strpos($line, '+') === 0 && strpos($line, '+++') !== 0) {
                    $addedLines++;
                } elseif (strpos($line, '-') === 0 && strpos($line, '---') !== 0) {
                    $removedLines++;
                }
            }

            $totalLinesChanged += $addedLines + $removedLines;
        }

        // If more than 10 lines changed, consider it a major change
        return $totalLinesChanged > 10 ? 'major' : 'minor';
    }

    /**
     * Bump version in config.php file
     */
    private function bumpVersion($configFile, $changeType)
    {
        // Read current config
        $config = include $configFile;

        if (!isset($config['version'])) {
            return false;
        }

        $currentVersion = $config['version'];

        // Parse version
        $versionParts = explode('.', $currentVersion);

        if (count($versionParts) !== 3) {
            return false;
        }

        list($major, $minor, $patch) = array_map('intval', $versionParts);

        // Bump version based on change type
        if ($changeType === 'major') {
            $minor++;
            $patch = 0;
        } else {
            $patch++;
        }

        $newVersion = "{$major}.{$minor}.{$patch}";

        // Update config.php file
        $configContent = file_get_contents($configFile);

        // Replace version line
        $pattern = '/(\'version\'\s*=>\s*\')([^\'"]+)(\',?)/m';
        $replacement = '${1}' . $newVersion . '${3}';

        $newContent = preg_replace($pattern, $replacement, $configContent);

        if ($newContent !== $configContent) {
            file_put_contents($configFile, $newContent);
            return $newVersion;
        }

        return false;
    }

    /**
     * Get current git commit hash
     */
    public function getCurrentCommit()
    {
        $output = [];
        exec('git rev-parse HEAD', $output);
        return trim($output[0] ?? '');
    }

    /**
     * Check if we're in a git repository
     */
    public function isGitRepository()
    {
        $output = [];
        exec('git rev-parse --git-dir 2>/dev/null', $output, $returnCode);
        return $returnCode === 0;
    }
}

// Command line interface
if ($argc > 1 && $argv[1] === '--help') {
    echo "RatePress Templates Version Bumper\n\n";
    echo "Usage: php version-bumper.php [templates-dir]\n\n";
    echo "Arguments:\n";
    echo "  templates-dir  Directory containing templates (default: templates)\n\n";
    echo "Description:\n";
    echo "  Checks git changes for each template and bumps version numbers:\n";
    echo "  - Minor change (+0.0.1): < 10 lines changed\n";
    echo "  - Major change (+0.1.0): >= 10 lines changed\n\n";
    echo "Examples:\n";
    echo "  php version-bumper.php\n";
    echo "  php version-bumper.php templates\n";
    exit(0);
}

$templatesDir = $argc > 1 ? $argv[1] : 'templates';

$bumper = new VersionBumper($templatesDir);

if (!$bumper->isGitRepository()) {
    echo "❌ Not a git repository. Version bumping requires git.\n";
    exit(1);
}

$bumper->bumpVersions();
?></content>
<parameter name="filePath">/Users/shayanmoradi/Desktop/Work/wp-ratepress/remote-templates/version-bumper.php