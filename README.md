# RatePress Community Templates

Welcome to the RatePress Community Templates repository! This is a collection of rating templates that can be installed directly into RatePress through the Templates marketplace.

## 🎨 Available Templates

Browse and install templates directly from your RatePress admin panel under **Settings → Templates**.

## 🚀 Creating a Template

### Required Files

Each template must be in its own folder with the following structure:

```
your-template-slug/
├── config.php      # Template configuration (required)
├── render.php      # PHP render function (required)
├── style.css       # Stylesheet (required)
├── script.js       # JavaScript (optional)
├── preview.png     # Preview image 800x600px (recommended)
└── readme.md       # Template documentation (optional)
```

### config.php Structure

```php
<?php
return [
    'label' => 'Your Template Name',
    'description' => 'A brief description of your template',
    'version' => '1.0.0',
    'author' => 'Your Name',
    'author_uri' => 'https://yourwebsite.com',
    'category' => 'scale', // 'binary', 'bipolar', or 'scale'
    'supports' => ['ajax', 'animation'], // Optional features
    'pro' => false, // true if requires RatePress Pro
    'min_ratepress_version' => '1.0.0'
];
```

### render.php Structure

```php
<?php
/**
 * Render callback for your template
 * 
 * @param array $data Template data
 *  - object_id: Post/object ID
 *  - object_type: 'post', 'comment', etc.
 *  - category: 'binary', 'bipolar', 'scale'
 *  - stats: Array of rating statistics
 *  - user_rating: Current user's rating (if any)
 *  - config: Template configuration
 */
function render_your_template($data) {
    $object_id = $data['object_id'];
    $category = $data['category'];
    $stats = $data['stats'][$category] ?? [];
    $user_rating = $data['user_rating'];
    
    ?>
    <div class="ratepress-template your-template-slug"
         data-object-id="<?php echo esc_attr($object_id); ?>"
         data-object-type="<?php echo esc_attr($data['object_type']); ?>"
         data-category="<?php echo esc_attr($category); ?>">
        
        <!-- Your template HTML here -->
        
    </div>
    <?php
}
```

### Data Attributes (Required)

Your template's root element must include these data attributes for JavaScript interaction:

- `data-object-id`: The post/object ID being rated
- `data-object-type`: Type of object ('post', 'comment', etc.)
- `data-category`: Rating category ('binary', 'bipolar', 'scale')

### JavaScript Integration

Use the RatePress core JavaScript API for rating submission:

```javascript
// Available in ratepress-core.js (automatically loaded)

// Submit a rating
RatePress.submitRating(objectId, objectType, category, value)
    .then(response => {
        // Handle success
        console.log('Rating submitted:', response.data);
    })
    .catch(error => {
        // Handle error
        console.error('Rating failed:', error);
    });

// Get rating stats
RatePress.getStats(objectId, objectType)
    .then(response => {
        // Handle stats
        console.log('Stats:', response.data.stats);
    });
```

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
