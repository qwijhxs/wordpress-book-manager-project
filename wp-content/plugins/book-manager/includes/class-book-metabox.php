<?php

class Book_Metabox {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_book_meta_boxes'));
        add_action('save_post', array($this, 'save_book_meta'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }


    public function add_book_meta_boxes() {
        add_meta_box(
            'book_basic_info',
            'Basic Book Information',
            array($this, 'render_basic_info_meta_box'),
            'book',
            'normal',
            'high'
        );

        add_meta_box(
            'book_additional_info',
            'Additional Information',
            array($this, 'render_additional_meta_box'),
            'book',
            'normal',
            'default'
        );

        add_meta_box(
            'book_technical_info',
            'Technical Details',
            array($this, 'render_technical_meta_box'),
            'book',
            'side',
            'default'
        );
    }

    public function enqueue_admin_scripts($hook) {
        if ('post.php' === $hook || 'post-new.php' === $hook) {
            $screen = get_current_screen();
            if ($screen && 'book' === $screen->post_type) {
                wp_enqueue_style(
                    'book-metabox-style',
                    plugin_dir_url(__FILE__) . '../assets/css/book-admin.css',
                    array(),
                    '1.0.0'
                );

                wp_enqueue_script(
                    'book-metabox-script',
                    plugin_dir_url(__FILE__) . '../assets/js/book-admin.js',
                    array('jquery'),
                    '1.0.0',
                    true
                );
            }
        }
    }

    public function render_basic_info_meta_box($post) {
        // Retrieve existing values
        $author = get_post_meta($post->ID, '_book_author', true);
        $year = get_post_meta($post->ID, '_book_year', true);
        $rating = get_post_meta($post->ID, '_book_rating', true);
        $publisher = get_post_meta($post->ID, '_book_publisher', true);

        wp_nonce_field('book_meta_save', 'book_meta_nonce');
        ?>
        
        <div class="book-metabox-fields">
            <div class="field-group">
                <label for="book_author">Author *</label>
                <input type="text" id="book_author" name="book_author" 
                       value="<?php echo esc_attr($author); ?>" 
                       class="widefat" required>
                <p class="description">Enter the full name of the author</p>
            </div>

            <div class="field-group">
                <label for="book_year">Publication Year *</label>
                <input type="number" id="book_year" name="book_year" 
                       value="<?php echo esc_attr($year); ?>" 
                       min="1000" max="<?php echo date('Y'); ?>" 
                       class="widefat" required>
                <p class="description">Year the book was first published</p>
            </div>

            <div class="field-group">
                <label for="book_rating">Rating (1-5)</label>
                <select id="book_rating" name="book_rating" class="widefat">
                    <option value="">Select Rating</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" 
                                <?php selected($rating, $i); ?>>
                            <?php echo $i; ?> - <?php echo str_repeat('★', $i); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="field-group">
                <label for="book_publisher">Publisher</label>
                <input type="text" id="book_publisher" name="book_publisher" 
                       value="<?php echo esc_attr($publisher); ?>" 
                       class="widefat">
                <p class="description">Name of the publishing company</p>
            </div>
        </div>
        
        <?php
    }

    public function render_additional_meta_box($post) {
        $genre = get_post_meta($post->ID, '_book_genre', true);
        $pages = get_post_meta($post->ID, '_book_pages', true);
        $language = get_post_meta($post->ID, '_book_language', true);
        $description = get_post_meta($post->ID, '_book_description', true);
        ?>
        
        <div class="book-metabox-fields">
            <div class="field-group">
                <label for="book_genre">Genre</label>
                <input type="text" id="book_genre" name="book_genre" 
                       value="<?php echo esc_attr($genre); ?>" 
                       class="widefat">
                <p class="description">e.g., Science Fiction, Mystery, Biography</p>
            </div>

            <div class="field-row">
                <div class="field-group half-width">
                    <label for="book_pages">Number of Pages</label>
                    <input type="number" id="book_pages" name="book_pages" 
                           value="<?php echo esc_attr($pages); ?>" 
                           min="1" class="widefat">
                </div>

                <div class="field-group half-width">
                    <label for="book_language">Language</label>
                    <input type="text" id="book_language" name="book_language" 
                           value="<?php echo esc_attr($language); ?>" 
                           class="widefat" placeholder="English">
                </div>
            </div>

            <div class="field-group">
                <label for="book_description">Book Description</label>
                <textarea id="book_description" name="book_description" 
                          rows="5" class="widefat"><?php echo esc_textarea($description); ?></textarea>
                <p class="description">Brief summary of the book (will be used in listings)</p>
            </div>
        </div>
        
        <?php
    }

    public function render_technical_meta_box($post) {
        $isbn = get_post_meta($post->ID, '_book_isbn', true);
        $format = get_post_meta($post->ID, '_book_format', true);
        $price = get_post_meta($post->ID, '_book_price', true);
        $available = get_post_meta($post->ID, '_book_available', true);
        ?>
        
        <div class="book-metabox-fields">
            <div class="field-group">
                <label for="book_isbn">ISBN</label>
                <input type="text" id="book_isbn" name="book_isbn" 
                       value="<?php echo esc_attr($isbn); ?>" 
                       class="widefat" pattern="[\d\-]+" 
                       title="Enter ISBN (numbers and hyphens only)">
                <p class="description">International Standard Book Number</p>
            </div>

            <div class="field-group">
                <label for="book_format">Format</label>
                <select id="book_format" name="book_format" class="widefat">
                    <option value="">Select Format</option>
                    <option value="hardcover" <?php selected($format, 'hardcover'); ?>>Hardcover</option>
                    <option value="paperback" <?php selected($format, 'paperback'); ?>>Paperback</option>
                    <option value="ebook" <?php selected($format, 'ebook'); ?>>E-book</option>
                    <option value="audiobook" <?php selected($format, 'audiobook'); ?>>Audiobook</option>
                </select>
            </div>

            <div class="field-group">
                <label for="book_price">Price ($)</label>
                <input type="number" id="book_price" name="book_price" 
                       value="<?php echo esc_attr($price); ?>" 
                       min="0" step="0.01" class="widefat">
                <p class="description">Current selling price</p>
            </div>

            <div class="field-group">
                <label for="book_available">
                    <input type="checkbox" id="book_available" name="book_available" 
                           value="yes" <?php checked($available, 'yes'); ?>>
                    Currently Available
                </label>
            </div>
        </div>
        
        <?php
    }


    public function save_book_meta($post_id, $post) {
        // Check if nonce is set and valid
        if (!isset($_POST['book_meta_nonce']) || 
            !wp_verify_nonce($_POST['book_meta_nonce'], 'book_meta_save')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if ('book' !== $post->post_type) {
            return;
        }

        $meta_fields = array(
            'book_author' => 'sanitize_text_field',
            'book_year' => 'intval',
            'book_rating' => 'intval',
            'book_publisher' => 'sanitize_text_field',
            'book_genre' => 'sanitize_text_field',
            'book_pages' => 'intval',
            'book_language' => 'sanitize_text_field',
            'book_description' => 'sanitize_textarea_field',
            'book_isbn' => 'sanitize_text_field',
            'book_format' => 'sanitize_text_field',
            'book_price' => 'floatval',
            'book_available' => array($this, 'sanitize_checkbox')
        );

        foreach ($meta_fields as $field => $sanitize_callback) {
            if (isset($_POST[$field])) {
                $value = call_user_func($sanitize_callback, $_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            } else {

                if ('book_available' === $field) {
                    delete_post_meta($post_id, '_' . $field);
                }
            }
        }

        $this->validate_required_fields($post_id, $_POST);
    }


    public function sanitize_checkbox($input) {
        return 'yes' === $input ? 'yes' : '';
    }


    private function validate_required_fields($post_id, $post_data) {
        $errors = array();

        if (empty($post_data['book_author'])) {
            $errors[] = 'Author is required.';
        }

        if (empty($post_data['book_year'])) {
            $errors[] = 'Publication year is required.';
        } elseif ($post_data['book_year'] < 1000 || $post_data['book_year'] > date('Y')) {
            $errors[] = 'Please enter a valid publication year.';
        }

        if (!empty($errors)) {
            set_transient('book_meta_errors_' . $post_id, $errors, 45);
            
            remove_action('save_post', array($this, 'save_book_meta'));
            

            if (isset($post_data['post_status']) && 'publish' === $post_data['post_status']) {
                global $wpdb;
                $wpdb->update($wpdb->posts, array('post_status' => 'draft'), array('ID' => $post_id));
            }
            
            add_action('save_post', array($this, 'save_book_meta'));
        }
    }

    public static function display_admin_notices() {
        global $post;
        
        if (!$post || 'book' !== $post->post_type) {
            return;
        }

        $errors = get_transient('book_meta_errors_' . $post->ID);
        if ($errors) {
            echo '<div class="error"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul></div>';

            delete_transient('book_meta_errors_' . $post->ID);
        }
    }


    public static function get_book_meta($post_id) {
        return array(
            'author' => get_post_meta($post_id, '_book_author', true),
            'year' => get_post_meta($post_id, '_book_year', true),
            'rating' => get_post_meta($post_id, '_book_rating', true),
            'publisher' => get_post_meta($post_id, '_book_publisher', true),
            'genre' => get_post_meta($post_id, '_book_genre', true),
            'pages' => get_post_meta($post_id, '_book_pages', true),
            'language' => get_post_meta($post_id, '_book_language', true),
            'description' => get_post_meta($post_id, '_book_description', true),
            'isbn' => get_post_meta($post_id, '_book_isbn', true),
            'format' => get_post_meta($post_id, '_book_format', true),
            'price' => get_post_meta($post_id, '_book_price', true),
            'available' => get_post_meta($post_id, '_book_available', true)
        );
    }
}

new Book_Metabox();

add_action('admin_notices', array('Book_Metabox', 'display_admin_notices'));