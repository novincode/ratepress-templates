# RatePress Community Templates

Welcome to the RatePress Community Templates repository! This is a collection of rating templates that can be installed directly into RatePress through the Templates marketplace.

## 🎨 Available Templates

Browse and install templates directly from your RatePress admin panel under **RatePress → Templates**.

Current templates:
- **modern/heart** - Glassmorphism heart icon with animations for binary love ratings

## 🚀 Creating a Template

### Template Naming

Templates use a slug-based naming system with namespaces:
- `simple/heart` - Simple heart template
- `modern/heart` - Modern glassmorphism heart  
- `minimal/stars` - Minimal star rating
- `your-namespace/template-name`

### Required Files

Each template must be in its own folder with the following structure:

```
namespace/template-name/
├── config.php      # Template configuration (required)
├── render.php      # PHP render function (required)
├── style.css       # Stylesheet (required)
├── script.js       # JavaScript (leave empty if using core JS)
└── preview.png     # Preview image 800x600px (optional)
```

### config.php Structure

```php
<?php
return [
    // Basic Information
    'slug' => 'namespace/template-name', // REQUIRED: Must match folder structure
    'name' => 'Your Template Name',
    'description' => 'A brief description of your template',
    'category' => 'binary', // 'binary', 'bipolar', or 'scale'
    'version' => '1.0.0',

    // Author & Attribution
    'author' => 'Your Name',
    'author_url' => 'https://yourwebsite.com',

    // Discovery & Distribution
    'tags' => ['tag1', 'tag2', 'tag3'], // For search and filtering
    'preview' => 'https://...', // Preview image URL
    'download_url' => 'https://...', // Download URL

    // Version Requirements
    'min_ratepress_version' => '1.0.0',

    // Technical Requirements
    'styles' => ['style.css'],
    'scripts' => [], // Leave empty if using core JS
    'requires_core_js' => true, // RatePress core JS handles interactions

    // Features & Capabilities
    'supports' => [
        'objects' => ['post', 'comment'], // Supported object types
        'responsive' => true,             // Mobile-friendly design
        'dark_mode' => true               // Supports dark mode
    ],

    // Demo & Preview Data
    'demo_data' => [
        'user_value' => 1,        // Sample user rating value
        'user_has_rated' => true, // Sample rating state
        'category_stats' => [
            'positive' => 128     // Sample rating counts
        ]
    ],

    // Customization Settings
    'settings' => [
        'setting_name' => [
            'type' => 'select', // 'select', 'boolean', 'text', etc.
            'label' => 'Display Label',
            'description' => 'Help text for users',
            'default' => 'default_value',
            'options' => [ // For select types
                'value' => 'Display Label'
            ]
        ]
    ]
];
```

### Automated Build Process

The `templates.json` manifest is **automatically generated** from template `config.php` files. You never need to edit `templates.json` manually!

**To regenerate templates.json locally:**
```bash
php build-templates.php
```

**GitHub Actions Setup:**
1. Create a [Personal Access Token](https://github.com/settings/tokens) with `repo` permissions
2. Add it as `TEMPLATES_BUILD_TOKEN` in repository Settings → Secrets and variables → Actions
3. The workflow will automatically build and commit `templates.json` when config.php files change

**What the workflow does:**
- Builds `templates.json` from all `config.php` files
- Commits changes back to the repository automatically
- Comments on PRs when manifests are updated

### render.php Structure

Study the core templates in `core/templates/simple/` for real working examples.

```php
<?php
namespace RatePress\Templates;

// Get template data
$data = $template_data ?? new TemplateData([]);
$stats = $data->category_stats ?? [];
$user_value = $data->user_value ?? 0;
$user_has_rated = $data->user_has_rated ?? false;
$object_id = $data->object_id ?? ($data->post_id ?? 0);
$object_type = $data->object_type ?? 'post';

// For binary: $is_active = $user_has_rated && $user_value > 0;
// For bipolar: $user_liked = $user_value > 0; $user_disliked = $user_value < 0;
// For scale: $display_average = $average * 5; (0.0-1.0 to 1-5 stars)
?>

<div class="ratepress-widget ratepress-your-template"
     data-object-id="<?php echo esc_attr($object_id); ?>"
     data-object-type="<?php echo esc_attr($object_type); ?>"
     data-category="binary"
     data-template="namespace/template-name"
     role="group">
     
    <button class="ratepress-btn" 
            type="button"
            data-value="1"
            aria-pressed="false">
        <!-- Your template HTML here -->
    </button>
</div>
```

### Data Attributes (Required)

Your template's root element must include these data attributes for RatePress core JS:

- `data-object-id`: The post/object ID being rated
- `data-object-type`: Type of object ('post', 'comment', etc.)
- `data-category`: Rating category ('binary', 'bipolar', 'scale')
- `data-template`: Template slug matching your folder structure
- Buttons must have `data-value` attribute (1 for binary, 1/-1 for bipolar, 0.2-1.0 for scale)

### JavaScript

**DO NOT write custom JavaScript** unless absolutely necessary. RatePress core JS automatically handles:
- Click events on elements with `data-value` attributes
- AJAX rating submission
- Real-time count updates
- Error handling
- Loading states

Leave `script.js` empty or add only template-specific animations/effects.

### Rating Categories & Values

#### Binary (Like/Heart)
- **Values**: `0` (not rated) or `1` (liked)
- **Use case**: Simple like/favorite buttons
- **Example**: Heart button, thumbs up

#### Bipolar (Like/Dislike)
- **Values**: `-1` (dislike), `0` (neutral), `1` (like)
- **Use case**: Like and dislike buttons
- **Example**: Thumbs up/down, yes/no

#### Scale (Stars)
- **Values**: `0.0` to `1.0` (internally), displayed as 1-5 stars
- **Use case**: Multi-level ratings
- **Example**: 5-star rating, 10-point scale

### CSS Best Practices

```css
/* Use BEM naming convention */
.ratepress-template.your-template-slug {
    /* Base styles */
}

.ratepress-template.your-template-slug .element {
    /* Element styles */
}

.ratepress-template.your-template-slug .element--modifier {
    /* Modifier styles */
}

/* Ensure mobile responsiveness */
@media (max-width: 768px) {
    .ratepress-template.your-template-slug {
        /* Mobile styles */
    }
}

/* Support dark mode */
@media (prefers-color-scheme: dark) {
    .ratepress-template.your-template-slug {
        /* Dark mode styles */
    }
}
```

### Preview Image

**Auto-generated!** The build script automatically creates preview images from your template's actual `render.php` and `style.css`.

- Just add `preview_background` to your `config.php` to customize the background color
- Or create a manual `preview.png` (640x400px) in your template folder
- See [PREVIEW_GENERATION.md](PREVIEW_GENERATION.md) for details

## 📤 Contributing

1. **Fork this repository**
2. **Create a new folder** with your template slug (lowercase-kebab-case)
3. **Add all required files** (see structure above)
4. **Test thoroughly** with different rating categories
5. **Update templates.json** (see below)
6. **Submit a Pull Request**

### Updating templates.json

**No longer needed!** The `templates.json` file is automatically generated from `config.php` files via GitHub Actions. Simply update your template's `config.php` and the manifest will be updated automatically.

## ✅ Template Checklist

Before submitting, ensure:

- [ ] All required files present (config.php, render.php, style.css)
- [ ] Template works with intended rating category
- [ ] Data attributes properly set for JavaScript interaction
- [ ] Mobile responsive design
- [ ] Dark mode support (recommended)
- [ ] Preview image included (800x600px)
- [ ] No hardcoded URLs or paths
- [ ] Proper escaping of output (esc_attr, esc_html, etc.)
- [ ] JavaScript uses RatePress.submitRating() API
- [ ] Tested in latest WordPress version
- [ ] `tags` array included in config.php for search functionality
- [ ] Repository has `TEMPLATES_BUILD_TOKEN` secret configured (one-time setup)

## 🎯 Design Guidelines

### Modern 2025 Standards

- **Glassmorphism**: Use backdrop filters for modern effects
- **Smooth Animations**: Cubic bezier transitions
- **Touch-Friendly**: Minimum 44x44px touch targets
- **Accessibility**: Proper ARIA labels, keyboard navigation
- **Performance**: Minimal DOM manipulation, efficient CSS

### Color Schemes

Consider supporting both light and dark modes:

```css
:root {
    --ratepress-primary: #3b82f6;
    --ratepress-success: #10b981;
    --ratepress-danger: #ef4444;
}

@media (prefers-color-scheme: dark) {
    :root {
        --ratepress-primary: #60a5fa;
        --ratepress-success: #34d399;
        --ratepress-danger: #f87171;
    }
}
```

## 🐛 Testing

Test your template with:

1. **Different Categories**: Binary, bipolar, scale
2. **User States**: Not rated, already rated, switching ratings
3. **Mobile Devices**: Various screen sizes
4. **Browsers**: Chrome, Firefox, Safari, Edge
5. **WordPress Versions**: Latest and previous major version
6. **Themes**: Popular themes (Astra, GeneratePress, etc.)

## 📝 License

All templates in this repository are licensed under GPL v2 or later, matching WordPress and RatePress licensing.

## 💬 Support

- **Documentation**: https://ratepress.com/docs
- **Issues**: https://github.com/novincode/ratepress-templates/issues
- **Community**: https://wordpress.org/support/plugin/ratepress

## 🌟 Featured Templates

Check out these community favorites:
- **modern-heart**: Animated heart with smooth bounce
- **glassmorphic-stars**: 5-star with glass effect
- **minimal-thumbs**: Clean thumbs up/down

---

Made with ❤️ by the RatePress Community
