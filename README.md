# Book Manager - WordPress Plugin & Theme

A complete WordPress solution for managing and displaying book collections. Includes both a custom plugin for book management and a theme for beautiful display.

## 📚 Features

### Plugin Features
- **Custom Post Type "Book"** - Easily add and manage your books from the WordPress admin
- **Custom Metaboxes** - Add book details like Author, Publication Year, and Rating
- **Shortcode Support** - Display books anywhere using `[book_list]` with pagination
- **Admin Interface** - Custom columns and styling in WordPress admin
- **Security** - Proper sanitization, validation, and nonce protection

### Theme Features
- **Custom Templates** - Specialized layouts for book archives and single book pages
- **Books Page Template** - Ready-to-use page template with book listing
- **Responsive Design** - Clean, modern CSS styling
- **WordPress Standards** - Proper template hierarchy and loop usage

## 🚀 Installation

### Method 1: Quick Setup (Recommended)

1. **Download the Project**
   - Clone: `git clone https://github.com/qwijhxs/wordpress-book-manager-project.git`
   - Or download ZIP from GitHub

2. **Install the Plugin**
   - Zip the `plugin` folder as `book-manager.zip`
   - In WordPress admin, go to `Plugins > Add New > Upload Plugin`
   - Upload `book-manager.zip` and activate

3. **Install the Theme**
   - Upload the `theme/intentionally-blank` folder to `/wp-content/themes/`
   - Go to `Appearance > Themes` and activate "Intentionally Blank"

### Method 2: Manual Installation

1. **Plugin Setup**
   ```bash
   # Copy plugin to WordPress
   cp -r plugin/book-manager /path/to/wordpress/wp-content/plugins/
   
   # Activate in WordPress admin: Plugins > Book Manager
Theme Setup

# Copy theme to WordPress
cp -r theme/intentionally-blank /path/to/wordpress/wp-content/themes/

# Activate in WordPress admin: Appearance > Themes
📖 Usage
Managing Books
Add New Books

Go to Books > Add New in WordPress admin

Add title, content, and featured image

Fill in custom fields: Author, Publication Year, Rating, Genre

Publish

Book Listing Shortcode

<!-- Basic usage -->
[book_list]

<!-- With parameters -->
[book_list posts_per_page="9" genre="Fiction"]

<!-- Filter by author -->
[book_list author="John Doe"]
Theme Features
Books Page

Create a new page in WordPress

Select "Books Template" from page attributes

The page will automatically display books with pagination

Custom Templates

single-book.php - Individual book pages

archive-book.php - Book category/archive pages

page-books.php - Custom books listing page

🏗️ Project Structure

wordpress-book-manager-project/
├── plugin/                 # Book Manager Plugin
│   ├── assets/
│   │   ├── css/           # Frontend styles
│   │   └── js/            # JavaScript files
│   ├── includes/          # Core PHP classes
│   │   ├── class-book-admin.php
│   │   ├── class-book-cpt.php
│   │   ├── class-book-metabox.php
│   │   └── class-book-shortcode.php
│   └── book-manager.php   # Main plugin file
├── theme/                 # Intentionally Blank Theme
│   ├── single-book.php    # Single book template
│   ├── archive-book.php   # Book archive template
│   ├── page-books.php     # Books page template
│   ├── style.css          # Theme styles
│   └── functions.php      # Theme functions
└── README.md              # This file
⚙️ Requirements
WordPress: 5.0 or higher

PHP: 7.4 or higher

MySQL: 5.6 or higher

🛠️ Customization
Shortcode Parameters
posts_per_page - Number of books to display (default: 6)

genre - Filter by genre

author - Filter by author

Styling
Plugin styles: plugin/assets/css/book-style.css

Theme styles: theme/style.css

❓ Troubleshooting
Books not appearing?

Ensure both plugin and theme are activated

Check that books are published

Verify custom fields are filled

Shortcode not working?

Confirm plugin is active

Check for JavaScript errors in browser console

Theme templates not loading?

Clear WordPress cache

Check file permissions

📄 License
This project is licensed under the GPL v2 or later.

🤝 Support
If you encounter any issues or have questions:

Check this README first

Open an issue on GitHub

Provide detailed description of your setup and the problem
