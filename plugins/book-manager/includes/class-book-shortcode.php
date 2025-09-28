<?php
class Book_Shortcode {
    
    public function __construct() {
        add_shortcode('book_list', array($this, 'render_book_list'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }
    
    public function enqueue_styles() {
        wp_enqueue_style('book-list-style', 
            plugins_url('../assets/css/book-style.css', __FILE__)
        );
    }
    
    public function render_book_list($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 6,
            'genre' => '',
            'author' => ''
        ), $atts);
        
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        
        $args = array(
            'post_type' => 'book',
            'posts_per_page' => $atts['posts_per_page'],
            'paged' => $paged,
            'meta_query' => array()
        );

        if (!empty($atts['genre'])) {
            $args['meta_query'][] = array(
                'key' => '_book_genre',
                'value' => $atts['genre'],
                'compare' => 'LIKE'
            );
        }

        if (!empty($atts['author'])) {
            $args['meta_query'][] = array(
                'key' => '_book_author',
                'value' => $atts['author'],
                'compare' => 'LIKE'
            );
        }
        
        $books_query = new WP_Query($args);
        
        ob_start();
        
        if ($books_query->have_posts()) {
            echo '<div class="book-list">';
            
            while ($books_query->have_posts()) {
                $books_query->the_post();
                $this->render_book_item();
            }
            
            echo '</div>';

            $this->render_pagination($books_query);
            
            wp_reset_postdata();
        } else {
            echo '<p>No books found.</p>';
        }
        
        return ob_get_clean();
    }
    
    private function render_book_item() {
        $author = get_post_meta(get_the_ID(), '_book_author', true);
        $rating = get_post_meta(get_the_ID(), '_book_rating', true);
        $genre = get_post_meta(get_the_ID(), '_book_genre', true);
        ?>
        <div class="book-item">
            <div class="book-cover">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                <?php else : ?>
                    <div class="book-cover-placeholder">
                        <span>No Cover</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="book-info">
                <h3 class="book-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                
                <?php if ($author) : ?>
                    <p class="book-author">by <?php echo esc_html($author); ?></p>
                <?php endif; ?>
                
                <?php if ($rating) : ?>
                    <div class="book-rating">
                        <div class="rating-stars">
                            <?php echo str_repeat('★', intval($rating)); ?>
                            <?php echo str_repeat('☆', 5 - intval($rating)); ?>
                        </div>
                        <span class="rating-value"><?php echo esc_html($rating); ?>/5</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($genre) : ?>
                    <p class="book-genre">Genre: <?php echo esc_html($genre); ?></p>
                <?php endif; ?>
                
                <a href="<?php the_permalink(); ?>" class="book-link">View Details</a>
            </div>
        </div>
        <?php
    }
    
    private function render_pagination($query) {
        $big = 999999999;
        echo '<div class="book-pagination">';
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $query->max_num_pages
        ));
        echo '</div>';
    }
}