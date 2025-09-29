<?php get_header(); ?>

<div class="home-page">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Discover Amazing Books</h1>
            <p>Explore our curated collection of literature from classic to contemporary authors</p>
            <a href="<?php echo get_permalink(get_page_by_path('books')); ?>" class="cta-button">
                Start Reading Journey
            </a>
        </div>
    </section>

    <!-- Recent Books -->
    <section class="recent-books">
        <div class="container">
            <h2>Recently Added</h2>
            <div class="books-grid">
                <?php 
                $books_query = new WP_Query(array(
                    'post_type' => 'book',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($books_query->have_posts()) :
                    while ($books_query->have_posts()) : 
                        $books_query->the_post();
                        $author = get_post_meta(get_the_ID(), '_book_author', true);
                        $rating = get_post_meta(get_the_ID(), '_book_rating', true);
                        $genre = get_post_meta(get_the_ID(), '_book_genre', true);
                        ?>
                        
                        <div class="book-card">
                            <div class="book-cover">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php else : ?>
                                    <div class="book-cover-placeholder">
                                        <span>📚 No Cover</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="book-info">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
                                    <span class="book-genre"><?php echo esc_html($genre); ?></span>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="book-link">Read More</a>
                            </div>
                        </div>
                        
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>No books found in our collection.</p>
                <?php endif; ?>
            </div>
            
            <div class="view-all-books">
                <a href="<?php echo get_permalink(get_page_by_path('books')); ?>" class="view-all-button">
                    View All Books
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-site">
        <div class="container">
            <h2>About Our Library</h2>
            <p>We are passionate about connecting readers with great literature. Our collection spans multiple genres, 
            time periods, and cultures. Whether you're looking for classic literature, modern fiction, or educational 
            resources, you'll find something to inspire your reading journey.</p>
        </div>
    </section>

</div>

<?php get_footer(); ?>