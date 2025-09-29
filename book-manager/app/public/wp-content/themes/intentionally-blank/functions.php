<?php
/**
 * Intentionally Blank Theme Functions
 * 
 * @package Intentionally_Blank
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ===== THEME SETUP =====
function intentionally_blank_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'intentionally-blank' ),
        'footer'  => esc_html__( 'Footer Menu', 'intentionally-blank' ),
    ) );
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );
    
    // Book specific image sizes
    add_image_size( 'book-cover-small', 150, 225, true );
    add_image_size( 'book-cover-medium', 300, 450, true );
    add_image_size( 'book-cover-large', 400, 600, true );
}
add_action( 'after_setup_theme', 'intentionally_blank_setup' );

// ===== ENQUEUE SCRIPTS & STYLES =====
function intentionally_blank_scripts() {
    wp_enqueue_style( 
        'intentionally-blank-style', 
        get_stylesheet_uri(), 
        array(), 
        wp_get_theme()->get( 'Version' ) 
    );
    
    wp_enqueue_style( 
        'intentionally-blank-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', 
        array(), 
        null 
    );

    if ( file_exists( get_template_directory() . '/js/main.js' ) ) {
        wp_enqueue_script( 
            'intentionally-blank-script', 
            get_template_directory_uri() . '/js/main.js', 
            array(), 
            wp_get_theme()->get( 'Version' ), 
            true 
        );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'intentionally_blank_scripts' );

// ===== WIDGET AREAS =====
function intentionally_blank_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'intentionally-blank' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'intentionally-blank' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets', 'intentionally-blank' ),
        'id'            => 'footer-widgets',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'intentionally-blank' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'intentionally_blank_widgets_init' );

// ===== CONTENT HELPERS =====
function intentionally_blank_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'intentionally_blank_excerpt_length' );

function intentionally_blank_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'intentionally_blank_excerpt_more' );

function intentionally_blank_body_classes( $classes ) {
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    if ( is_singular( 'book' ) || is_post_type_archive( 'book' ) || is_page( 'books' ) ) {
        $classes[] = 'books-page';
    }
    
    return $classes;
}
add_filter( 'body_class', 'intentionally_blank_body_classes' );

function intentionally_blank_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'intentionally_blank_pingback_header' );

// ===== THEME SPECIFIC FUNCTIONS =====
function intentionally_blank_custom_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        ?>
        <div class="text-logo">
            <span class="logo-icon">📚</span>
            <div class="site-titles">
                <h1 class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                    </a>
                </h1>
                <?php if ( get_bloginfo( 'description' ) ) : ?>
                    <p class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

function intentionally_blank_get_book_meta( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    return array(
        'author'    => get_post_meta( $post_id, 'book_author', true ), // FIXED: was _book_author
        'year'      => get_post_meta( $post_id, 'book_year', true ),   // FIXED: was _book_year
        'rating'    => get_post_meta( $post_id, 'book_rating', true ), // FIXED: was _book_rating
    );
}

function intentionally_blank_display_book_rating( $rating ) {
    if ( ! $rating || ! is_numeric( $rating ) ) {
        return '';
    }
    
    $rating = intval( $rating );
    $stars = str_repeat( '★', $rating );
    $empty_stars = str_repeat( '☆', 5 - $rating );
    
    return '<div class="rating-stars">' . esc_html( $stars . $empty_stars ) . '</div>';
}

// ===== BOOK ARCHIVE QUERY MODIFICATION =====
function intentionally_blank_modify_book_archive_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( is_post_type_archive( 'book' ) ) {
            $query->set( 'posts_per_page', 12 );
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
        }
    }
}
add_action( 'pre_get_posts', 'intentionally_blank_modify_book_archive_query' );

// ===== THEME ACTIVATION =====
function intentionally_blank_theme_activation() {
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    
    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );

        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'   => esc_html__( 'Home', 'intentionally-blank' ),
            'menu-item-url'     => home_url( '/' ),
            'menu-item-status'  => 'publish',
        ) );
        
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'   => esc_html__( 'Books', 'intentionally-blank' ),
            'menu-item-url'     => home_url( '/books/' ),
            'menu-item-status'  => 'publish',
        ) );

        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // Create books page if it doesn't exist
    if ( ! get_page_by_path( 'books' ) ) {
        $books_page = array(
            'post_title'    => wp_strip_all_tags( __( 'Books', 'intentionally-blank' ) ),
            'post_content'  => '[book_list]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'books',
        );
        wp_insert_post( $books_page );
    }
}
add_action( 'after_switch_theme', 'intentionally_blank_theme_activation' );

// ===== MENU TITLE FIXES =====

add_filter('wp_nav_menu_objects', 'intentionally_blank_fix_book_menu_titles');
function intentionally_blank_fix_book_menu_titles($menu_items) {
    foreach ($menu_items as $menu_item) {
        // Fix archive book titles
        if ($menu_item->type === 'post_type_archive' && 
            $menu_item->object === 'book' && 
            (strpos($menu_item->title, '(no title)') !== false || empty(trim($menu_item->title)))) {
            $menu_item->title = esc_html__( 'Books', 'intentionally-blank' );
        }
        
        // Fix custom links to books
        if ($menu_item->type === 'custom' && 
            (strpos($menu_item->url, 'post_type=book') !== false || strpos($menu_item->url, '/books/') !== false) && 
            (strpos($menu_item->title, '(no title)') !== false || empty(trim($menu_item->title)))) {
            $menu_item->title = esc_html__( 'Books', 'intentionally-blank' );
        }
    }
    return $menu_items;
}

// ===== SECURITY ENHANCEMENTS =====

function intentionally_blank_remove_version() {
    return '';
}
add_filter( 'the_generator', 'intentionally_blank_remove_version' );

function intentionally_blank_disable_xmlrpc() {
    add_filter( 'xmlrpc_enabled', '__return_false' );
}
?>