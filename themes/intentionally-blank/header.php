<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        if (is_singular('book')) {
            $author = get_post_meta(get_the_ID(), '_book_author', true);
            echo get_the_title() . ' by ' . $author . ' | ' . get_bloginfo('name');
        } else {
            wp_title('|', true, 'right');
            bloginfo('name');
        }
        ?>
    </title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" rel="home">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <div class="text-logo">
                                <span class="logo-icon">📚</span>
                                <div class="site-titles">
                                    <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                                    <p class="site-description"><?php bloginfo('description'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>

                <nav class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'nav-menu',
                        'container' => false
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="site-main">