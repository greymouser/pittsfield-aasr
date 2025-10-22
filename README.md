# Pittsfield AASR Website

A static website for the Valley of Pittsfield, Ancient and Accepted Scottish Rite, Northern Masonic Jurisdiction.

## Overview

This is a responsive, static website designed for the Pittsfield AASR Scottish Rite group in Berkshire County, Massachusetts. The site includes a main landing page, about page, and contact page, with a focus on Scottish Rite Masonic traditions and the local Valley's history.

## Structure

```
pittsfield-aasr/
├── index.html          # Main landing page
├── about.html          # About the Valley page
├── contact.html        # Contact information page
├── css/
│   ├── colors.css      # Color palette and variables
│   └── main.css        # Main stylesheet
├── js/
│   └── main.js         # Interactive functionality
├── images/
│   ├── sr-logo.svg     # Scottish Rite logo
│   ├── double-eagle.png    # Double-headed eagle symbol
│   ├── compass-square.svg  # Masonic compass and square
│   ├── fellowship.svg      # Brotherhood symbol
│   ├── community.svg       # Community service symbol
│   ├── scottish-rite-temple.jpg  # Temple image placeholder
│   └── ritual-chamber.jpg       # Ritual chamber image placeholder
└── README.md           # This file
```

## Features

- **Responsive Design**: Mobile-friendly layout that works on all devices
- **Easy Color Customization**: Simple color system in `css/colors.css`
- **Scottish Rite Themed**: Appropriate imagery and symbolism
- **Contact Form**: Ready-to-implement contact form (requires backend)
- **SEO Friendly**: Proper meta tags and semantic HTML structure
- **Accessibility**: ARIA labels and keyboard navigation support

## Customization

### Changing Colors

The entire site's color scheme can be easily modified by editing the base colors in `css/colors.css`:

```css
:root {
  --primary-base: #1a237e;    /* Deep blue - main brand color */
  --secondary-base: #8b0000;  /* Dark red - accent color */
  --tertiary-base: #ffd700;   /* Gold - highlight color */
}
```

Alternative color schemes are provided as comments in the same file. Simply uncomment one to use it.

### Content Updates

1. **Leadership Information**: Update officer names in `about.html`
2. **Contact Details**: Modify contact information in `contact.html`
3. **Meeting Information**: Update meeting schedules and locations as needed
4. **History**: Adjust the historical information in the about page

### Images

Replace the placeholder SVG files in the `images/` directory with actual photographs:

- `scottish-rite-temple.jpg`: Replace with actual temple/lodge photos
- `ritual-chamber.jpg`: Replace with appropriate ritual chamber images
- Add your own Valley-specific imagery as needed

### Contact Form

The contact form is currently front-end only. To make it functional, you'll need to:

1. Set up a backend service (PHP, Node.js, etc.) or use a service like Formspree
2. Update the `action` attribute in the contact form
3. Implement server-side validation and email sending

## Local Development

To run the site locally:

1. Clone or download the files
2. Open `index.html` in a web browser
3. For development with live reload, use a local server:
   ```bash
   # Using Python 3
   python -m http.server 8000
   
   # Using Node.js (if you have http-server installed)
   npx http-server
   ```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Scottish Rite Information

The site includes information specific to the Valley of Pittsfield:

- **Jurisdiction**: Northern Masonic Jurisdiction
- **Type**: Lodge of Perfection (4° through 14°)
- **Status**: Rechartered in 2023
- **Region**: Berkshire County, Massachusetts
- **Meeting Pattern**: Rotating locations throughout North and South Berkshire County

## Compliance and Traditions

The website design and content respect Scottish Rite traditions and Masonic principles:

- Appropriate use of Scottish Rite symbolism
- Emphasis on brotherhood, education, and service
- Professional presentation suitable for a Masonic organization
- Respect for the privacy and dignity of the Craft

## Support

For technical support or questions about the website, contact the Valley Secretary at the email address provided on the contact page.

## License

This website template is provided for the exclusive use of the Valley of Pittsfield, Ancient and Accepted Scottish Rite. All Scottish Rite symbols and references are used with respect for Masonic tradition and should not be used outside of appropriate Masonic contexts.

---

*Created for the Valley of Pittsfield AASR - Northern Masonic Jurisdiction*