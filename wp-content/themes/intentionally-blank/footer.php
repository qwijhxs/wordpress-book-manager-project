    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                </div>
                
                <nav class="footer-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'container' => false,
                        'depth' => 1
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>