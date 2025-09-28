<?php
/**
 * Template Name: Books Archive
 * Template Post Type: book
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                if (is_post_type_archive('book')) {
                    esc_html_e('Books Library', 'intentionally-blank');
                } elseif (is_tax('book_genre')) {
                    echo esc_html__('Genre: ', 'intentionally-blank');
                    single_term_title();
                } else {
                    the_archive_title();
                }
                ?>
            </h1>
        </header>
        <?php
        $genres = get_terms(array(
            'taxonomy' => 'book_genre',
            'hide_empty' => true,
        ));
        if ($genres && !is_wp_error($genres) && count($genres) > 0) : ?>
            <div class="books-filter">
                <a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>"
                   class="filter-btn <?php echo is_post_type_archive('book') ? 'active' : ''; ?>">
                    <?php esc_html_e('All Books', 'intentionally-blank'); ?>
                </a>
                <?php foreach ($genres as $genre) : ?>
                    <a href="<?php echo esc_url(get_term_link($genre)); ?>"
                       class="filter-btn <?php echo is_tax('book_genre', $genre->term_id) ? 'active' : ''; ?>">
                        <?php echo esc_html($genre->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="books-archive">
            <?php if (have_posts()) : ?>
                <div class="books-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('book-item book-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="book-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('book-cover-medium', array(
                                            'alt' => esc_attr(get_the_title())
                                        )); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                           
                            <div class="book-content">
                                <h2 class="book-title">
                                    <a href="<?php the_permalink(); ?>" class="book-link">
                                        <?php echo esc_html(get_the_title()); ?>
                                    </a>
                                </h2>
                               
                                <div class="book-meta">
                                    <?php
                                    $author = get_post_meta(get_the_ID(), 'book_author', true);
                                    if ($author) {
                                        echo '<p class="book-author"><strong>' . esc_html__('Author:', 'intentionally-blank') . '</strong> ' . esc_html($author) . '</p>';
                                    }
                                    $year = get_post_meta(get_the_ID(), 'book_year', true);
                                    if ($year) {
                                        echo '<p class="book-year"><strong>' . esc_html__('Publication Year:', 'intentionally-blank') . '</strong> ' . esc_html($year) . '</p>';
                                    }
                                    $rating = get_post_meta(get_the_ID(), 'book_rating', true);
                                    if ($rating) {
                                        $stars = str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating));
                                        echo '<p class="book-rating"><strong>' . esc_html__('Rating:', 'intentionally-blank') . '</strong> <span class="rating-stars">' . esc_html($stars) . '</span></p>';
                                    }
                                    $genres = get_the_terms(get_the_ID(), 'book_genre');
                                    if ($genres && !is_wp_error($genres)) {
                                        echo '<p class="book-genres"><strong>' . esc_html__('Genres:', 'intentionally-blank') . '</strong> ';
                                        $genre_names = array();
                                        foreach ($genres as $genre) {
                                            $genre_names[] = '<span class="genre-tag">' . esc_html($genre->name) . '</span>';
                                        }
                                        echo implode(', ', $genre_names);
                                        echo '</p>';
                                    }
                                    ?>
                                </div>
                               
                                <a href="<?php the_permalink(); ?>" class="book-read-more">
                                    <?php esc_html_e('Read more →', 'intentionally-blank'); ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php
                the_posts_pagination(array(
                    'prev_text' => esc_html__('← Previous', 'intentionally-blank'),
                    'next_text' => esc_html__('Next →', 'intentionally-blank'),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'intentionally-blank') . ' </span>',
                ));
                ?>
            <?php else : ?>
                <div class="no-books-message">
                    <p><?php esc_html_e('No books found.', 'intentionally-blank'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>