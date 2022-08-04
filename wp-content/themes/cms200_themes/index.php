<?php get_header(); ?>

<div id="carousell">
    <img src="https://via.placeholder.com/1400x300?text=HEADER-BILD" alt="Headerbild" />
</div> <!-- /carousell -->
        <div class="content">

            <?php get_sidebar('left'); ?>

            <section class="main">
                <?php if(have_posts() ) : while (have_posts() ) : the_post(); ?>
                <article class="post">
                    <header class="post">
                        <section class="entry-title">
                            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" 
                            rel="bookmark"><?php the_title(); ?></a></h2>
                        </section> <!-- /entry-title -->
                        <scetion class="entry-meta">
                            <p>Written by <?php the_author_posts_link(); ?>
                            on <?php the_time('j F Y'); ?>
                            in <?php the_category(', '); ?>.
                            <?php if(comments_open()) {
                                echo '<span class="comments-link"><a href="' . get_comments_link() . '">Comment!</a></span>';
                            }
                            ?>
                    </header> <!-- /article post -->
                    <section class="entry-content">
                        <?php the_content(); ?>
                    </section> <!-- /entry-content -->
                    <footer class="post">

                    </footer> <!-- /footer.post -->
                </article> <!-- /post -->
                <?php endwhile; else: ?>
                    <section class="entry-title">
                        <h2>Doh!</h2>
                    </section> <!-- /enrty-title -->
                    <section class="entry-content">
                        <p>Theres nothing in here...</p>
                    </section> <!-- /entry-content -->
                <?php endif; ?>

            </section> <!-- /section -->

            <?php get_sidebar('right'); ?>
            
        </div> <!-- /content -->

<?php get_footer(); ?>