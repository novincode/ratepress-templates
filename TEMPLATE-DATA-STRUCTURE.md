# RateKit Template Data Structure v2

## Headless & Scalable Template System

This document defines the standardized data attribute structure that ALL RateKit templates must follow. The core JS uses ONLY these data attributes, making it completely template-agnostic.

---

## Widget Container Attributes

Every rating widget must have these attributes on the root element:

```html
<div class="ratekit-widget TEMPLATE-SPECIFIC-CLASS"
     data-object-id="123"
     data-object-type="post"
     data-category="scale"
     data-template="vendor/template-name"
     data-user-rating="0.8">
```

### Required Attributes:
- `data-object-id`: ID of the object being rated (post/comment ID)
- `data-object-type`: Type of object (`post` or `comment`)
- `data-category`: Rating category (`binary`, `bipolar`, or `scale`)
- `data-template`: Template identifier (e.g., `modern/stars`, `neon/neon-dots-5`)

### Optional Attributes:
- `data-user-rating`: User's current rating (0-1 normalized value, set by PHP)
- `data-size`: Size variant (`small`, `medium`, `large`)
- `data-theme`: Theme variant (`light` or `dark`)

---

## Rating Buttons/Inputs

### Binary Category (0 or 1)
Single button with value="1":

```html
<button type="button" data-value="1">
    <!-- Icon/Content -->
</button>
```

### Bipolar Category (-1, 0, 1)
Two buttons for positive/negative:

```html
<button type="button" data-value="1">Like</button>
<button type="button" data-value="-1">Dislike</button>
```

### Scale Category (0.0 to 1.0)
Multiple buttons with normalized values (0.2, 0.4, 0.6, 0.8, 1.0 for 5-point scale):

```html
<!-- 5-point scale (stars/dots) -->
<button type="button" data-value="0.2">1</button>
<button type="button" data-value="0.4">2</button>
<button type="button" data-value="0.6">3</button>
<button type="button" data-value="0.8">4</button>
<button type="button" data-value="1.0">5</button>

<!-- 10-point scale -->
<button type="button" data-value="0.1">1</button>
<button type="button" data-value="0.2">2</button>
<!-- ... -->
<button type="button" data-value="1.0">10</button>
```

### Range Input (Slider)
```html
<input type="range" 
       min="0" 
       max="10" 
       step="0.1" 
       value="7.5"
       data-value="0.75">
```
Note: Core JS auto-normalizes range values to 0-1 scale.

---

## Display Elements

### Binary Category
Shows count of positive ratings:

```html
<span data-count="positive">42</span>
```

### Bipolar Category
Shows positive and negative counts:

```html
<span data-count="positive">128</span>
<span data-count="negative">15</span>

<!-- Optional: Combined score -->
<span data-count="score">113</span>
```

### Scale Category
Shows average and total ratings:

```html
<!-- Average rating (auto-multiplied by scale) -->
<span data-stat="average" data-scale="5">4.2</span>

<!-- Total number of ratings -->
<span data-stat="total">156</span>

<!-- Example with text -->
<span data-stat="average" data-scale="5">4.2</span> out of 5
(<span data-stat="total">156</span> ratings)
```

**Important**: 
- `data-scale` attribute tells core JS what scale to use (5 for stars, 10 for dots, etc.)
- Average is stored as 0-1 in database, core JS multiplies by scale for display
- Total is the count of all ratings

---

## Active State Management

The core JS automatically adds/removes the `.active` class on buttons based on user ratings.

### Your CSS should style active buttons:
```css
.my-button {
    /* Default state */
}

.my-button.active {
    /* Active/selected state */
}
```

---

## Complete Examples

### Binary Template (Heart/Like)
```html
<div class="ratekit-widget my-heart"
     data-object-id="123"
     data-object-type="post"
     data-category="binary"
     data-template="vendor/heart"
     data-user-rating="1">
    
    <button type="button" data-value="1" aria-pressed="true">
        <svg><!-- heart icon --></svg>
        <span data-count="positive">42</span>
    </button>
</div>
```

### Bipolar Template (Like/Dislike)
```html
<div class="ratekit-widget my-likedislike"
     data-object-id="123"
     data-object-type="post"
     data-category="bipolar"
     data-template="vendor/likedislike"
     data-user-rating="1">
    
    <button type="button" data-value="1" class="active">
        <svg><!-- thumbs up --></svg>
        <span data-count="positive">128</span>
    </button>
    
    <button type="button" data-value="-1">
        <svg><!-- thumbs down --></svg>
        <span data-count="negative">15</span>
    </button>
</div>
```

### Scale Template (5 Stars)
```html
<div class="ratekit-widget my-stars"
     data-object-id="123"
     data-object-type="post"
     data-category="scale"
     data-template="vendor/stars"
     data-user-rating="0.8">
    
    <div class="star-buttons">
        <button type="button" data-value="0.2" class="active">★</button>
        <button type="button" data-value="0.4" class="active">★</button>
        <button type="button" data-value="0.6" class="active">★</button>
        <button type="button" data-value="0.8" class="active">★</button>
        <button type="button" data-value="1.0">★</button>
    </div>
    
    <div class="stats">
        <span data-stat="average" data-scale="5">4.2</span> stars
        (<span data-stat="total">156</span> ratings)
    </div>
</div>
```

### Scale Template (10 Dots)
```html
<div class="ratekit-widget my-dots"
     data-object-id="123"
     data-object-type="post"
     data-category="scale"
     data-template="vendor/dots-10"
     data-user-rating="0.7">
    
    <div class="dot-buttons">
        <button type="button" data-value="0.1" class="active">●</button>
        <button type="button" data-value="0.2" class="active">●</button>
        <button type="button" data-value="0.3" class="active">●</button>
        <button type="button" data-value="0.4" class="active">●</button>
        <button type="button" data-value="0.5" class="active">●</button>
        <button type="button" data-value="0.6" class="active">●</button>
        <button type="button" data-value="0.7" class="active">●</button>
        <button type="button" data-value="0.8">●</button>
        <button type="button" data-value="0.9">●</button>
        <button type="button" data-value="1.0">●</button>
    </div>
    
    <div class="stats">
        <span data-stat="average" data-scale="10">7.3</span>/10
        (<span data-stat="total">203</span> ratings)
    </div>
</div>
```

### Scale Template (Range Slider)
```html
<div class="ratekit-widget my-range"
     data-object-id="123"
     data-object-type="post"
     data-category="scale"
     data-template="vendor/range"
     data-user-rating="0.75">
    
    <div class="range-container">
        <span data-display="value">7.5</span>
        
        <input type="range" 
               min="0" 
               max="10" 
               step="0.1" 
               value="7.5">
        
        <div class="labels">
            <span>0</span>
            <span>10</span>
        </div>
    </div>
    
    <div class="stats">
        Average: <span data-stat="average" data-scale="10">7.8</span>/10
        (<span data-stat="total">89</span> ratings)
    </div>
</div>
```

---

## Key Principles

1. **Headless**: Core JS doesn't care about your HTML structure or class names
2. **Data-Driven**: All functionality is controlled by data attributes
3. **Normalized Values**: All rating values are 0-1 in database, scaled for display
4. **Category-Based**: Binary, bipolar, and scale categories have specific patterns
5. **Auto-Update**: Core JS automatically updates all widgets for the same object
6. **Flexible Design**: Use any HTML, CSS, icons, animations you want

---

## Testing Checklist

When creating a new template, verify:

- [ ] Widget has `data-object-id`, `data-object-type`, `data-category`, `data-template`
- [ ] All buttons/inputs have `data-value` with correct normalized values
- [ ] Display elements have `data-count` (binary/bipolar) or `data-stat` (scale)
- [ ] Scale templates include `data-scale` attribute on average displays
- [ ] CSS includes `.active` class styling for selected state
- [ ] Clicking a rating submits and updates immediately (no page refresh)
- [ ] Multiple widgets for same object update together
- [ ] Works in both light and dark themes (if supported)

---

## Migration from Old Structure

### Old (v1) - Class-based:
```html
<button class="ratekit-star-btn active">★</button>
<span class="ratekit-average">4.2</span>
<span class="ratekit-count">156</span>
```

### New (v2) - Data-based:
```html
<button data-value="0.2" class="active">★</button>
<span data-stat="average" data-scale="5">4.2</span>
<span data-stat="total">156</span>
```

**Changes:**
- `ratekit-star-btn` → use any class, just needs `data-value`
- `ratekit-average` → `data-stat="average"` with `data-scale`
- `ratekit-count` → `data-stat="total"`
