<div class="container alignwide">
    <div class="row">
        <?php if (is_array($posts) && ! is_wp_error($posts)): ?>
            <?php foreach($posts as $post): ?>
            <div class="col-sm col-md-4">
                <img src="<?php echo $post->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes->full->source_url; ?>" width="100%" height="210px" />
                <h3><a href="<?php echo $post->link; ?>"><?php echo $post->title->rendered; ?></a></h3>
            </div>
            <?php endforeach; ?>    
        <?php else: ?>
            <div class="col-sm">    
                <h1><?php _e('No hay resultados', 'blocks'); ?></h1>
            </div>
        <?php endif; ?>
    </div>
</div>