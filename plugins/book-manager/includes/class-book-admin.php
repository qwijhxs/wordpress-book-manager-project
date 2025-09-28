<?php
class BookManager_Admin {
    
    public function __construct() {
        add_filter('manage_book_posts_columns', [$this, 'add_admin_columns']);
        add_action('manage_book_posts_custom_column', [$this, 'render_admin_columns'], 10, 2);
        add_filter('manage_edit-book_sortable_columns', [$this, 'add_sortable_columns']);
    }
    
    public function add_admin_columns($columns) {
        $new_columns = [];
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['book_author'] = __('Author', 'book-manager');
                $new_columns['book_year'] = __('Year', 'book-manager');
                $new_columns['book_rating'] = __('Rating', 'book-manager');
            }
        }
        
        return $new_columns;
    }
    
    public function render_admin_columns($column, $post_id) {
        switch ($column) {
            case 'book_author':
                echo esc_html(get_post_meta($post_id, 'book_author', true));
                break;
                
            case 'book_year':
                echo esc_html(get_post_meta($post_id, 'book_year', true));
                break;
                
            case 'book_rating':
                $rating = get_post_meta($post_id, 'book_rating', true);
                if ($rating) {
                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                }
                break;
        }
    }
    
    public function add_sortable_columns($columns) {
        $columns['book_author'] = 'book_author';
        $columns['book_year'] = 'book_year';
        $columns['book_rating'] = 'book_rating';
        return $columns;
    }
}