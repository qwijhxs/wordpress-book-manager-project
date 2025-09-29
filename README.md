# Book Manager WordPress Plugin

A simple and powerful WordPress plugin to manage a collection of books on your website. It creates a custom post type "Book" with custom metaboxes and provides a shortcode for easy display.

## Features

- **Custom Post Type "Book"**: Easily add and manage your books from the WordPress admin.
- **Custom Metaboxes**: Add additional information like Author, ISBN, and Publication Year to your books.
- **Shortcode Support**: Display your books anywhere on the site using the `[book_list]` shortcode.
- **Custom Admin Styling**: Improved user experience in the WordPress admin area.

## Installation

1. **Download the Plugin**: Clone this repository or download it as a ZIP file.
2. **Upload to WordPress**:
   - Navigate to your WordPress admin dashboard.
   - Go to `Plugins > Add New`.
   - Click `Upload Plugin` and choose the downloaded ZIP file.
   - Click `Install Now` and then `Activate`.
3. **Alternative Manual Installation**:
   - Unzip the downloaded file.
   - Upload the `book-manager` folder to your `/wp-content/plugins/` directory.
   - Go to the `Plugins` menu in WordPress and activate the "Book Manager" plugin.

## Usage

### Adding a New Book

1. After activation, you will see a new "Books" menu item in your WordPress admin sidebar.
2. Click on `Books > Add New`.
3. Add a title and description for your book as you would with a standard post.
4. Fill in the custom fields in the "Book Details" metabox (Author, ISBN, Publication Year).
5. Publish your book.

### Displaying Books on Your Site

Use the provided shortcode to display a list of all your books on any post or page.

[book_list]

Simply paste this shortcode into the content editor where you want the books to appear.

## File Structure
book-manager/
├── assets/
│ ├── css/ # Stylesheets
│ └── js/ # JavaScript files
├── includes/ # Core PHP classes
└── book-manager.php # Main plugin file

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## Support

If you encounter any issues or have questions, please open an issue in this GitHub repository.

## License

This plugin is licensed under the GPL v2 or later.
