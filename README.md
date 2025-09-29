📚 Book Manager - WordPress Plugin and Theme
https://img.shields.io/badge/WordPress-6.0%252B-blue
https://img.shields.io/badge/PHP-7.4%252B-purple
https://img.shields.io/badge/License-MIT-green

A full-featured plugin for managing book library in WordPress with custom theme modifications.

🎯 About the Project
This project demonstrates skills in:

WordPress Custom Post Types

Meta Fields and Taxonomies

Shortcodes and Pagination

OOP Plugin Architecture

Security (nonce, sanitization, escaping)

✨ Features
🔧 Book Manager Plugin
✅ Custom Post Type "Books"

✅ Meta Fields (Author, Publication Year, Rating)

✅ [book_list] Shortcode with Pagination

✅ Admin Interface for Book Management

✅ Custom Taxonomy "Book Genres"

🎨 Theme Modifications
✅ Custom Book Single Template

✅ Books Page Template with Shortcode

✅ Responsive CSS Styling

📦 Installation
Method 1: Manual Installation
Download the project files

Copy the wp-content folder to your WordPress root directory:

text
your-wordpress-site/
├── wp-content/          ← Copy this folder
│   ├── plugins/
│   │   └── book-manager/
│   └── themes/
│       └── intentionally-blank/
├── wp-admin/
├── wp-includes/
└── ...
Activate the Plugin:

Go to WordPress Admin → Plugins

Find "Book Manager" and click "Activate"

Activate the Theme:

Go to WordPress Admin → Appearance → Themes

Activate "Intentionally Blank" theme

Create Books Page:

Go to Pages → Add New

Title: "Books"

In the content area add: [book_list]

In Page Attributes select template: "Books Page"

Publish the page

Method 2: Using Git
bash
# Clone the repository
git clone https://github.com/your-username/book-manager.git

# Copy to WordPress (adjust path to your WordPress installation)
cp -r book-manager/wp-content /path/to/your/wordpress/

# Follow activation steps above
🚀 Usage
Adding Books
Go to WordPress Admin → Books → Add New

Fill in book details:

Title: Book name

Content: Book description

Featured Image: Book cover

Meta Fields: Author, Year, Rating

Genres: Assign book genres

Using the Shortcode
Basic usage:

php
[book_list]
With parameters:

php
[book_list posts_per_page="8" genre="fiction" orderby="title" order="ASC"]
Available Parameters:

posts_per_page - Number of books per page (default: 6)

genre - Filter by genre slug

orderby - title, date, meta_value (default: date)

order - ASC or DESC (default: DESC)

Accessing Books
Books Archive: yoursite.com/books/

Single Book: yoursite.com/books/book-slug/

Books Page: yoursite.com/books/ (the page you created)

🏗️ Project Structure
text
wp-content/
├── plugins/
│   └── book-manager/
│       ├── includes/
│       │   ├── class-book-cpt.php          # Custom Post Type
│       │   ├── class-book-metabox.php      # Meta Fields
│       │   ├── class-book-shortcode.php    # Shortcode
│       │   └── class-book-admin.php        # Admin Columns
│       ├── assets/
│       │   └── css/
│       │       └── book-style.css          # Plugin Styles
│       ├── book-manager.php                # Main Plugin File
│       └── index.php                       # Security
└── themes/
    └── intentionally-blank/
        ├── page-books.php                  # Books Page Template
        ├── single-book.php                 # Single Book Template
        └── ... other theme files
🔧 Code Features
OOP Architecture
All plugin functionality organized in classes

Proper separation of concerns

Easy to extend and maintain

Security Implementation
Nonce verification for all forms

Input sanitization and validation

Output escaping

Capability checks

WordPress Standards
Proper use of hooks (actions and filters)

Internationalization ready

Follows WordPress coding standards

🎨 Customization
Adding New Meta Fields
Edit class-book-metabox.php:

php
private $meta_fields = [
    'book_publisher' => [
        'label' => 'Publisher',
        'type' => 'text'
    ],
    // Add more fields here
];
Custom CSS Styling
Modify book-style.css in the plugin or add custom CSS to your theme.

📋 Requirements
WordPress 6.0+

PHP 7.4+

MySQL 5.6+

🐛 Troubleshooting
Books Not Appearing?
Check that the plugin is activated

Verify permalinks are saved (Settings → Permalinks → Save)

Clear any caching plugins

Shortcode Not Working?
Ensure the page uses the "Books Page" template

Check that the shortcode is correctly formatted

Verify there are published books

403 Forbidden Error?
Check file permissions (folders: 755, files: 644)

Verify all files are in correct locations

Check server error logs for details

🤝 Contributing
Fork the project

Create your feature branch (git checkout -b feature/AmazingFeature)

Commit your changes (git commit -m 'Add some AmazingFeature')

Push to the branch (git push origin feature/AmazingFeature)

Open a Pull Request

👨‍💻 Author
Vladyslava Ishchuk
ischukvladislava6@gmail.com

🙏 Acknowledgments
WordPress Developer Documentation

Local by Flywheel for development environment

Intentionally Blank theme authors

⭐ If you find this project useful, please give it a star!

text

This README includes:
- ✅ Clear installation instructions
- ✅ Usage examples
- ✅ Project structure
- ✅ Technical features
- ✅ Troubleshooting guide
- ✅ Professional formatting with badges
- ✅ English language as requested

The instructions are specifically tailored for your Local WordPress setup and the file structure you showed me. 🚀
