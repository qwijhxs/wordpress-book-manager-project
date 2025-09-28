<?php
class BookManager_CPT {
    
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomy']);
    }
    
    public function register_post_type() {
        $labels = [
            'name' => __('Books', 'book-manager'),
            'singular_name' => __('Book', 'book-manager'),
            'add_new' => __('Add New Book', 'book-manager'),
            'add_new_item' => __('Add New Book', 'book-manager'),
            'edit_item' => __('Edit Book', 'book-manager'),
            'new_item' => __('New Book', 'book-manager'),
            'view_item' => __('View Book', 'book-manager'),
            'search_items' => __('Search Books', 'book-manager'),
            'not_found' => __('No Books Found', 'book-manager'),
            'menu_name' => __('Books', 'book-manager'),
        ];
        
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'books'],
        ];
        
        register_post_type('book', $args);
    }
    
    public function register_taxonomy() {
        $labels = [
            'name' => __('Genres', 'book-manager'),
            'singular_name' => __('Genre', 'book-manager'),
        ];
        
        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'show_in_rest' => true,
        ];
        
        register_taxonomy('book_genre', 'book', $args);
    }
}