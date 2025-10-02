# Contributing to RatePress Templates

Thank you for considering contributing to the RatePress Templates collection! This document provides guidelines for contributing templates.

## Code of Conduct

- Be respectful and constructive
- Help others learn and grow
- Focus on what's best for the community
- Show empathy towards other community members

## Getting Started

1. Fork the repository
2. Create a feature branch (`git checkout -b template/my-awesome-template`)
3. Develop your template following the guidelines below
4. Test thoroughly
5. Submit a Pull Request

## Template Requirements

### Must Have
- ✅ Unique slug (lowercase-kebab-case)
- ✅ Valid `config.php` with all required fields
- ✅ Working `render.php` with proper data handling
- ✅ `style.css` with responsive design
- ✅ Proper data attributes for JavaScript interaction
- ✅ Preview image (800x600px PNG)
- ✅ Updated `templates.json` entry

### Should Have
- ✅ `script.js` for enhanced interactivity
- ✅ `readme.md` with usage instructions
- ✅ Dark mode support
- ✅ Accessibility features (ARIA labels, keyboard nav)
- ✅ Mobile-first responsive design
- ✅ Cross-browser compatibility

### Nice to Have
- ✅ Advanced animations
- ✅ Multiple color scheme options
- ✅ RTL (Right-to-Left) support
- ✅ Customization options

## Code Quality

### PHP
- Follow WordPress Coding Standards
- Use proper escaping (`esc_attr`, `esc_html`, `esc_url`)
- No direct database queries
- No hardcoded paths or URLs
- Proper sanitization of all inputs

### JavaScript
- Use modern ES6+ syntax
- Leverage RatePress core API
- Handle errors gracefully
- No jQuery dependencies (unless necessary)
- Proper event delegation

### CSS
- Use BEM naming convention
- Mobile-first approach
- Support for `prefers-color-scheme`
- No `!important` unless absolutely necessary
- Prefix classes with `ratepress-`

## Testing Checklist

Before submitting, test your template with:

- [ ] Binary category (like/heart buttons)
- [ ] Bipolar category (like/dislike buttons)
- [ ] Scale category (star ratings)
- [ ] First-time rating
- [ ] Updating existing rating
- [ ] Mobile devices (< 768px)
- [ ] Tablet devices (768px - 1024px)
- [ ] Desktop (> 1024px)
- [ ] Chrome, Firefox, Safari, Edge
- [ ] Light and dark modes
- [ ] WordPress 5.0+ compatibility
- [ ] Popular themes (Astra, GeneratePress, Twenty Twenty-*)

## Pull Request Process

1. **Title**: Use format `feat: add [template-name] template`
2. **Description**: Include:
   - Template name and category
   - Screenshot or GIF
   - Key features
   - Browser/device testing results
   - Any dependencies or requirements

3. **Files**: Ensure PR includes:
   - Template folder with all files
   - Updated `templates.json`
   - Preview image

4. **Review**: Wait for maintainer review
   - Address any feedback promptly
   - Be open to suggestions
   - Update as needed

## Versioning

Templates use [Semantic Versioning](https://semver.org/):

- **MAJOR**: Incompatible API changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

## License

All contributions must be licensed under GPL v2 or later, matching WordPress licensing.

## Questions?

- **Issues**: Open a GitHub issue for bugs or feature requests
- **Discussion**: Use GitHub Discussions for general questions
- **Documentation**: Check README.md for detailed guides

## Recognition

Contributors will be:
- Listed in the template's `config.php` as author
- Mentioned in release notes
- Featured on the RatePress website (with permission)

Thank you for contributing! 🎉
