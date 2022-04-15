<?php
/**
 * The template for displaying all single event
 *
 */
get_header();?>
    <div class="single-event">
    <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
            $post_id =  get_the_ID();
            $title =  get_the_title();
            $featureImg =  get_the_post_thumbnail_url();
            $content = get_the_content();
            if(get_post_meta($post->ID , 'event_date', true))
            $event_date = get_post_meta($post->ID , 'event_date', true);
            if(get_post_meta($post->ID , 'event_url', true))
            $event_url = get_post_meta($post->ID , 'event_url', true);
            if(get_post_meta($post->ID , 'event_location', true))
            $event_location = get_post_meta($post->ID , 'event_location', true); 
            ?>  
            <div class='container'><h1><?php echo $title; ?></h1>
                <div class="feature_img"><img src ="<?php echo $featureImg; ?>"></div>
                    <div class="post_content"><?php echo $content; ?></div>
                    <div class="date"><span class='event_label'>Date of event: </span><?php echo $event_date; ?></div>
                    <div class="url"><span class='event_label'>Event url: </span><a href="<?php echo $event_url; ?>" target="_blank">Event URL</a></div>
                    <div class="location"><span class='event_label'>Location of event: </span><?php echo $event_location; ?></div>
                <!-- Download calender file-->
                <form method="post" action="?event_details=true">
                    <input type="hidden" name="start_date" value="<?php echo $event_date ; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $event_date; ?>">
                    <input type="hidden" name="location" value="<?php echo $event_location; ?>">
                    <input type="hidden" name="url" value="<?php echo $event_url; ?>">
                    <input type="hidden" name="summary" value="">
                    <input type="submit" class="btn btn-primary" value="Add to Calendar">
                </form>
                 <!-- Show Map-->
                <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $event_location; ?>&amp;output=embed"></iframe><br />
            </div> 
        <?php endwhile; ?>
    </div>
    <!-- .content-area -->
<?php get_footer(); ?>