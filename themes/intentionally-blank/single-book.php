<?php
/**
 * Single Book Template
 */

get_header();
?>

<div class="book-single-container">
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="book-<?php the_ID(); ?>" <?php post_class('book-detail'); ?>>
            <div class="book-header">
                <h1 class="book-title"><?php echo esc_html(get_the_title()); ?></h1>
                <div class="book-meta-header">
                    <?php 

                    $author = get_post_meta(get_the_ID(), 'book_author', true);
                    $year = get_post_meta(get_the_ID(), 'book_year', true);
                    $rating = get_post_meta(get_the_ID(), 'book_rating', true);
                    ?>
                </div>
            </div>

            <div class="book-content-wrapper">
                <div class="book-cover-section">
                    <div class="book-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php 
                            the_post_thumbnail('book-cover-large', array(
                                'class' => 'book-cover',
                                'alt' => esc_attr(get_the_title())
                            )); 
                            ?>
                        <?php else : ?>
                            <div class="book-cover-placeholder">
                                <span><?php esc_html_e('No Cover Image', 'intentionally-blank'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($rating) : ?>
                        <div class="book-rating-large">
                            <div class="rating-stars">
                                <?php 
                                echo esc_html(str_repeat('★', intval($rating))); 
                                echo esc_html(str_repeat('☆', 5 - intval($rating))); 
                                ?>
                            </div>
                            <span class="rating-value">
                                <?php 
                                printf(
                                    esc_html__('%d/5', 'intentionally-blank'),
                                    intval($rating)
                                ); 
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="book-details-section">
                    <section class="book-description">
                        <h3><?php esc_html_e('About the Book', 'intentionally-blank'); ?></h3>
                        <div class="description-content">
                            <?php 
                            the_content(); 

                            if (empty(get_the_content())) {
                                echo '<p>' . esc_html__('No description available for this book.', 'intentionally-blank') . '</p>';
                            }
                            ?>
                        </div>
                    </section>

                    <?php

                    $additional_details = array();

                    $additional_fields = array(
                        'genre' => get_post_meta(get_the_ID(), '_book_genre', true),
                        'pages' => get_post_meta(get_the_ID(), '_book_pages', true),
                        'isbn' => get_post_meta(get_the_ID(), '_book_isbn', true),
                        'publisher' => get_post_meta(get_the_ID(), '_book_publisher', true),
                        'language' => get_post_meta(get_the_ID(), '_book_language', true),
                        'format' => get_post_meta(get_the_ID(), '_book_format', true),
                    );

                    $additional_details = array_filter($additional_fields);
                    ?>
                    
                    <?php if (!empty($additional_details)) : ?>
                        <div class="book-info-grid">
                            <h4><?php esc_html_e('Book Details', 'intentionally-blank'); ?></h4>
                            
                            <?php if (!empty($additional_fields['genre'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('Genre:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html($additional_fields['genre']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_fields['pages'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('Pages:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html($additional_fields['pages']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_fields['isbn'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('ISBN:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html($additional_fields['isbn']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_fields['publisher'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('Publisher:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html($additional_fields['publisher']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_fields['language'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('Language:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html($additional_fields['language']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($additional_fields['format'])) : ?>
                                <div class="info-item">
                                    <span class="info-label"><?php esc_html_e('Format:', 'intentionally-blank'); ?></span>
                                    <span class="info-value"><?php echo esc_html(ucfirst($additional_fields['format'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>