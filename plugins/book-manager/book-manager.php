<?php
/**
 * Plugin Name: Book Manager
 * Description: Manage books with custom post type and shortcode
 * Version: 1.0.0
 * Text Domain: book-manager
 */

if (!defined('ABSPATH')) exit;

class BookManager {
    
    public function __construct() {
        add_action('init', [$this, 'init_plugin']);
        add_action('add_meta_boxes', [$this, 'add_book_meta_box']);
        add_action('save_post', [$this, 'save_book_meta']);
        add_shortcode('book_list', [$this, 'book_list_shortcode']);
        add_action('wp_head', [$this, 'add_book_styles']);
        add_filter('the_content', [$this, 'modify_single_book_content']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }
    
    public function init_plugin() {
        $this->register_book_post_type();
        load_plugin_textdomain('book-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    private function register_book_post_type() {
        register_post_type('book', [
            'labels' => [
                'name' => __('Books', 'book-manager'),
                'singular_name' => __('Book', 'book-manager'),
                'add_new' => __('Add New Book', 'book-manager'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book',
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_rest' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true
        ]);
    }
    
    public function add_book_meta_box() {
        add_meta_box('book_details', __('Book Details', 'book-manager'), [$this, 'render_book_meta_box'], 'book', 'normal', 'high');
    }
    
    public function render_book_meta_box($post) {
        wp_nonce_field('book_meta_nonce', 'book_meta_nonce_field');
        
        $author = get_post_meta($post->ID, 'book_author', true);
        $year = get_post_meta($post->ID, 'book_year', true);
        $rating = get_post_meta($post->ID, 'book_rating', true);
        ?>
        <div style="display: grid; gap: 15px;">
            <div>
                <label for="book_author"><strong><?php esc_html_e('Author:', 'book-manager'); ?></strong></label><br>
                <input type="text" id="book_author" name="book_author" value="<?php echo esc_attr($author); ?>" style="width: 100%; padding: 8px;">
            </div>
            <div>
                <label for="book_year"><strong><?php esc_html_e('Publication Year:', 'book-manager'); ?></strong></label><br>
                <input type="number" id="book_year" name="book_year" min="1000" max="<?php echo esc_attr(date('Y')); ?>" value="<?php echo esc_attr($year); ?>" style="width: 100%; padding: 8px;">
            </div>
            <div>
                <label for="book_rating"><strong><?php esc_html_e('Rating (1-5):', 'book-manager'); ?></strong></label><br>
                <select id="book_rating" name="book_rating" style="width: 100%; padding: 8px;">
                    <option value=""><?php esc_html_e('Select Rating', 'book-manager'); ?></option>
                    <option value="1" <?php selected($rating, '1'); ?>>★☆☆☆☆</option>
                    <option value="2" <?php selected($rating, '2'); ?>>★★☆☆☆</option>
                    <option value="3" <?php selected($rating, '3'); ?>>★★★☆☆</option>
                    <option value="4" <?php selected($rating, '4'); ?>>★★★★☆</option>
                    <option value="5" <?php selected($rating, '5'); ?>>★★★★★</option>
                </select>
            </div>
        </div>
        <?php
    }
    
    public function save_book_meta($post_id) {

        if (!isset($_POST['book_meta_nonce_field']) || !wp_verify_nonce($_POST['book_meta_nonce_field'], 'book_meta_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $fields = [
            'book_author' => 'sanitize_text_field',
            'book_year' => 'intval',
            'book_rating' => 'intval'
        ];
        
        foreach ($fields as $field => $sanitizer) {
            if (isset($_POST[$field])) {
                $value = call_user_func($sanitizer, $_POST[$field]);

                if ($field === 'book_year' && ($value < 1000 || $value > date('Y'))) {
                    continue;
                }
                
                if ($field === 'book_rating' && ($value < 1 || $value > 5)) {
                    continue;
                }
                
                update_post_meta($post_id, $field, $value);
            }
        }
    }
    
    public function book_list_shortcode($atts = []) {
        $atts = shortcode_atts([
            'posts_per_page' => 50,
            'orderby' => 'title',
            'order' => 'ASC'
        ], $atts, 'book_list');
        
        $books = new WP_Query([
            'post_type' => 'book',
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby' => sanitize_sql_orderby($atts['orderby']),
            'order' => in_array(strtoupper($atts['order']), ['ASC', 'DESC']) ? $atts['order'] : 'ASC',
            'post_status' => 'publish'
        ]);
        
        if (!$books->have_posts()) {
            return '<p>' . esc_html__('No books found.', 'book-manager') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="book-manager-container">
            <h1 class="book-page-title"><?php esc_html_e('Books', 'book-manager'); ?></h1>
            <p class="book-page-description"><?php esc_html_e('List of all books in the library', 'book-manager'); ?></p>
            
            <div class="books-grid">
                <?php while ($books->have_posts()) : $books->the_post(); ?>
                    <?php
                    $author = get_post_meta(get_the_ID(), 'book_author', true);
                    $year = get_post_meta(get_the_ID(), 'book_year', true);
                    $rating = get_post_meta(get_the_ID(), 'book_rating', true);
                    ?>
                    
                    <div class="book-card">
                        <h2 class="book-title">
                            <a href="<?php the_permalink(); ?>" class="book-link"><?php echo esc_html(get_the_title()); ?></a>
                        </h2>
                        <div class="book-meta">
                            <?php if ($author) : ?>
                                <p><strong><?php esc_html_e('Author:', 'book-manager'); ?></strong> <?php echo esc_html($author); ?></p>
                            <?php endif; ?>
                            <?php if ($year) : ?>
                                <p><strong><?php esc_html_e('Publication Year:', 'book-manager'); ?></strong> <?php echo esc_html($year); ?></p>
                            <?php endif; ?>
                            <?php if ($rating) : ?>
                                <p><strong><?php esc_html_e('Rating:', 'book-manager'); ?></strong> <span class="book-rating"><?php echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); ?></span></p>
                            <?php endif; ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="book-read-more"><?php esc_html_e('Read more →', 'book-manager'); ?></a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    public function modify_single_book_content($content) {
        if (is_singular('book') && in_the_loop() && is_main_query()) {
            global $post;
            
            $author = get_post_meta($post->ID, 'book_author', true);
            $year = get_post_meta($post->ID, 'book_year', true);
            $rating = get_post_meta($post->ID, 'book_rating', true);
            
            ob_start();
            ?>
            <div class="book-single-container">
                <div class="book-single-header">
                    <h1 class="book-single-title"><?php echo esc_html(get_the_title()); ?></h1>
                    <?php if ($author) : ?>
                        <p class="book-single-author"><?php printf(esc_html__('by %s', 'book-manager'), esc_html($author)); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="book-single-content">
                    <div class="book-info-card">
                        <h3><?php esc_html_e('Book Information', 'book-manager'); ?></h3>
                        <div class="book-meta">
                            <?php if ($author) : ?>
                                <p><strong><?php esc_html_e('Author:', 'book-manager'); ?></strong> <?php echo esc_html($author); ?></p>
                            <?php endif; ?>
                            <?php if ($year) : ?>
                                <p><strong><?php esc_html_e('Publication Year:', 'book-manager'); ?></strong> <?php echo esc_html($year); ?></p>
                            <?php endif; ?>
                            <?php if ($rating) : ?>
                                <p><strong><?php esc_html_e('Rating:', 'book-manager'); ?></strong> <span class="book-rating"><?php echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); ?></span></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($content)) : ?>
                        <div class="book-description">
                            <h3><?php esc_html_e('About the Book', 'book-manager'); ?></h3>
                            <?php echo wp_kses_post($content); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
        return $content;
    }
    
    public function add_book_styles() {
        if (is_post_type_archive('book') || is_singular('book') || (is_singular() && has_shortcode(get_post()->post_content, 'book_list'))) {
            echo '<style>';
            echo $this->get_book_styles();
            echo '</style>';
        }
    }
    
    public function admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post;
        if ($post && 'book' === $post->post_type) {
            wp_enqueue_script('book-manager-admin', plugins_url('js/admin.js', __FILE__), ['jquery'], '1.0.0', true);
        }
    }
    
    private function get_book_styles() {
 $css = '.book-manager-container,';
        $css .= '.book-single-container,';
        $css .= '.post-type-archive-book .site-main,';
        $css .= '.single-book .site-main {';
        $css .= 'max-width: 1200px !important;';
        $css .= 'margin: 0 auto !important;';
        $css .= 'padding: 40px 20px !important;';
        $css .= 'font-family: Georgia, serif !important;';
        $css .= 'background: #fefaf0 !important;';
        $css .= 'color: #5d4037 !important;';
        $css .= 'line-height: 1.6 !important;';
        $css .= 'box-sizing: border-box !important;';
        $css .= '}';
        
        $css .= '.post-type-archive-book .page-title,';
        $css .= '.post-type-archive-book .archive-description,';
        $css .= '.single-book .entry-header {';
        $css .= 'display: none !important;';
        $css .= '}';
        
        $css .= '.book-page-title {';
        $css .= 'color: #6d4c41 !important;';
        $css .= 'text-align: center !important;';
        $css .= 'border-bottom: 2px solid #d7ccc8 !important;';
        $css .= 'padding-bottom: 15px !important;';
        $css .= 'font-size: 2.5em !important;';
        $css .= 'margin-bottom: 10px !important;';
        $css .= '}';
        
        $css .= '.book-page-description {';
        $css .= 'text-align: center !important;';
        $css .= 'color: #8d6e63 !important;';
        $css .= 'font-style: italic !important;';
        $css .= 'margin-bottom: 40px !important;';
        $css .= 'font-size: 1.1em !important;';
        $css .= '}';
        
        $css .= '.books-grid {';
        $css .= 'display: grid !important;';
        $css .= 'grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;';
        $css .= 'gap: 30px !important;';
        $css .= '}';
        
        $css .= '.book-card {';
        $css .= 'background: white !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'padding: 30px !important;';
        $css .= 'box-shadow: 0 4px 20px rgba(141, 110, 99, 0.1) !important;';
        $css .= 'border-left: 5px solid #ffb74d !important;';
        $css .= 'transition: all 0.3s ease !important;';
        $css .= 'display: flex !important;';
        $css .= 'flex-direction: column !important;';
        $css .= 'height: 100% !important;';
        $css .= '}';
        
        $css .= '.book-card:hover {';
        $css .= 'transform: translateY(-5px) !important;';
        $css .= 'box-shadow: 0 8px 30px rgba(141, 110, 99, 0.2) !important;';
        $css .= '}';
        
        $css .= '.book-title {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'margin: 0 0 15px 0 !important;';
        $css .= 'font-size: 1.6em !important;';
        $css .= 'line-height: 1.3 !important;';
        $css .= '}';
        
        $css .= '.book-link {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'text-decoration: none !important;';
        $css .= 'transition: color 0.3s ease !important;';
        $css .= '}';
        
        $css .= '.book-link:hover {';
        $css .= 'color: #8d6e63 !important;';
        $css .= '}';
        
        $css .= '.book-meta {';
        $css .= 'flex-grow: 1 !important;';
        $css .= 'margin-bottom: 20px !important;';
        $css .= '}';
        
        $css .= '.book-meta p {';
        $css .= 'margin: 12px 0 !important;';
        $css .= 'color: #6d4c41 !important;';
        $css .= 'font-size: 1.1em !important;';
        $css .= '}';
        
        $css .= '.book-meta strong {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'min-width: 140px !important;';
        $css .= 'display: inline-block !important;';
        $css .= '}';
        
        $css .= '.book-rating {';
        $css .= 'color: #ff9800 !important;';
        $css .= 'font-weight: bold !important;';
        $css .= 'font-size: 1.2em !important;';
        $css .= '}';
        
        $css .= '.book-read-more {';
        $css .= 'color: #8d6e63 !important;';
        $css .= 'text-decoration: none !important;';
        $css .= 'font-weight: bold !important;';
        $css .= 'align-self: flex-start !important;';
        $css .= 'transition: color 0.3s ease !important;';
        $css .= '}';
        
        $css .= '.book-read-more:hover {';
        $css .= 'color: #5d4037 !important;';
        $css .= '}';
        
        $css .= '.book-single-header {';
        $css .= 'text-align: center !important;';
        $css .= 'margin-bottom: 40px !important;';
        $css .= 'padding: 40px !important;';
        $css .= 'background: white !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'box-shadow: 0 4px 20px rgba(141, 110, 99, 0.1) !important;';
        $css .= '}';
        
        $css .= '.book-single-title {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'font-size: 2.5em !important;';
        $css .= 'margin: 0 0 10px 0 !important;';
        $css .= '}';
        
        $css .= '.book-single-author {';
        $css .= 'color: #8d6e63 !important;';
        $css .= 'font-size: 1.3em !important;';
        $css .= 'font-style: italic !important;';
        $css .= 'margin: 0 !important;';
        $css .= '}';
        
        $css .= '.book-single-content {';
        $css .= 'display: grid !important;';
        $css .= 'grid-template-columns: 1fr 2fr !important;';
        $css .= 'gap: 30px !important;';
        $css .= '}';
        
        $css .= '.book-info-card {';
        $css .= 'background: white !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'padding: 30px !important;';
        $css .= 'box-shadow: 0 4px 20px rgba(141, 110, 99, 0.1) !important;';
        $css .= 'height: fit-content !important;';
        $css .= '}';
        
        $css .= '.book-info-card h3 {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'border-bottom: 2px solid #ffb74d !important;';
        $css .= 'padding-bottom: 10px !important;';
        $css .= 'margin-top: 0 !important;';
        $css .= '}';
        
        $css .= '.book-description {';
        $css .= 'background: white !important;';
        $css .= 'border-radius: 12px !important;';
        $css .= 'padding: 30px !important;';
        $css .= 'box-shadow: 0 4px 20px rgba(141, 110, 99, 0.1) !important;';
        $css .= '}';
        
        $css .= '.book-description h3 {';
        $css .= 'color: #5d4037 !important;';
        $css .= 'border-bottom: 2px solid #ffb74d !important;';
        $css .= 'padding-bottom: 10px !important;';
        $css .= 'margin-top: 0 !important;';
        $css .= '}';
        
        $css .= '@media (max-width: 768px) {';
        $css .= '.book-single-content {';
        $css .= 'grid-template-columns: 1fr !important;';
        $css .= '}';
        $css .= '.books-grid {';
        $css .= 'grid-template-columns: 1fr !important;';
        $css .= '}';
        $css .= '.book-manager-container,';
        $css .= '.book-single-container {';
        $css .= 'padding: 20px 15px !important;';
        $css .= '}';
        $css .= '.book-single-title {';
        $css .= 'font-size: 2em !important;';
        $css .= '}';
        $css .= '}';
        
        $css .= '@media (max-width: 480px) {';
        $css .= '.book-card {';
        $css .= 'padding: 20px !important;';
        $css .= '}';
        $css .= '.book-single-header {';
        $css .= 'padding: 20px !important;';
        $css .= '}';
        $css .= '.book-page-title {';
        $css .= 'font-size: 2em !important;';
        $css .= '}';
        $css .= '}';
        
        return $css;
    }
}

new BookManager();