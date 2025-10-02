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
    'slug' => 'namespace/template-name', // REQUIRED: Must match folder structure
    'name' => 'Your Template Name',
    'description' => 'A brief description of your template',
    'version' => '1.0.0',
    'author' => 'Your Name',
    'author_url' => 'https://yourwebsite.com',
    'category' => 'binary', // 'binary', 'bipolar', or 'scale'
    'styles' => ['style.css'],
    'scripts' => [], // Leave empty if using core JS
    'requires_core_js' => true, // RatePress core JS handles interactions
    'supports' => [
        'objects' => ['post', 'comment'],
        'responsive' => true,
        'dark_mode' => true
    ],
    'settings' => [
        'icon_size' => [
            'type' => 'select',
            'default' => 'medium',
            'options' => [
                'small' => '20px',
                'medium' => '24px',
                'large' => '28px'
            ]
        ],
        'show_counts' => [
            'type' => 'boolean',
            'default' => true
        ]
    ],
    'min_ratepress_version' => '1.0.0'
];
```

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

Create a `preview.png` file (800x600px recommended) showing your template in action. This will be displayed in the Templates marketplace.

## 📤 Contributing

1. **Fork this repository**
2. **Create a new folder** with your template slug (lowercase-kebab-case)
3. **Add all required files** (see structure above)
4. **Test thoroughly** with different rating categories
5. **Update templates.json** (see below)
6. **Submit a Pull Request**

### Updating templates.json

Add your template to the `templates.json` manifest:

```json
{
    "slug": "your-template-slug",
    "name": "Your Template Name",
    "description": "Brief description",
    "version": "1.0.0",
    "author": "Your Name",
    "author_uri": "https://yourwebsite.com",
    "category": "scale",
    "supports": ["ajax", "animation"],
    "pro": false,
    "preview_url": "https://raw.githubusercontent.com/novincode/ratepress-templates/main/templates/your-template-slug/preview.png",
    "download_url": "https://github.com/novincode/ratepress-templates/archive/refs/heads/main/templates/your-template-slug.zip",
    "min_ratepress_version": "1.0.0",
    "tags": ["modern", "minimalist", "animated"]
}
```

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
- [ ] templates.json updated with your template

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
