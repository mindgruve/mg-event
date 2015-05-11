
<?php if ( $events && $events->have_posts() ) : ?>

    <?php if ($title) : ?>
        <?php echo $before_title . $title . $after_title; ?>
    <?php endif; ?>

    <ul class="mg-events">

        <?php while ($events->have_posts()) : $events->the_post(); ?>

            <li class="mg-event">

                <?php if ($showThumbnail && has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>

                <h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>

                <?php if ($showDate) : ?>
                    <time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
                <?php endif; ?>

                <?php echo implode(" ",array_splice(explode(' ', strip_tags(get_the_content())), 0, $wordCount)); ?>

            </li>

        <?php endwhile; ?>

    </ul>

<?php endif; ?>


<?php if ( $posts && $posts->have_posts() ) : ?>

    <ul class="mg-event-posts">

        <?php while ($posts->have_posts()) : $posts->the_post(); ?>

            <li class="mg-event-post">

                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>

                <h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>

            </li>

        <?php endwhile; ?>

    </ul>

<?php endif; ?>

