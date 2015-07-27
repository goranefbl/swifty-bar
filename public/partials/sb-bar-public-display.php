<?php
	global $post;
	$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);

	$post_id = get_queried_object_id();
	$author_id = get_post_field( 'post_author', $post_id );
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$comment_count = get_post_field( 'comment_count', $post_id );
	$content = get_post_field( 'post_content', $post_id );
	$category = get_the_category($post_id);
	$word_count = str_word_count( strip_tags( $content ) );
	$post_type = get_post_type_object( get_post_type($post_id) ); //Needed for custom post types
	$adjacent = false; //show prev/next post items
	$tag_or_cat = "category"; // prev/next cat or tag
	$posttype = array();


	//Post Type
	if(isset($options["post-type"]) && $options["post-type"] != '') {
		$posttype = $options["post-type"];
	}

	//Post Type
	if(isset($options["custom-color"]) && $options["custom-color"] != '') {
		$color = $options["custom-color"];
	} else {
		$color = "default";
	}

	//TTR
	if(isset($options["wpm-text"]) && $options["wpm-text"] != '') {
		$ttr = round($word_count / $options["wpm-text"]);
	} else {
		$ttr = round($word_count / 250);
	}
	if($ttr == 0) {
		$ttr = '<1';
	}

	//comments ID
	if(isset($options["comment-box-id"]) && $options["comment-box-id"] != '') {
		$commentsID = $options["comment-box-id"];
	} else {
		$commentsID = "comments";
	}

	// Time to read text
	if(isset($options["ttr-text"]) && $options["ttr-text"] != '') {
		$ttr_text = $options["ttr-text"];
	} else {
		$ttr_text = "time to read:";
	}

	// Word by
	if(isset($options["by-text"]) && $options["by-text"] != '') {
		$by_text = $options["by-text"];
	} else {
		$by_text = "by";
	}

	//New posts or from category
	if(isset($options["prev-next-posts"])) {
		if($options["prev-next-posts"] == 'cat') {
			$adjacent = true;
			$tag_or_cat = "category";
		} else if ($options["prev-next-posts"] == 'tags') {
			$adjacent = true;
			$tag_or_cat = "post_tag";
		}
	}



	if(is_singular() && in_array(get_post_type( $post_id ), $posttype)) {
	?>
	<!-- Swifty Bar -->
	<div id="sb_super_bar" class="<?php echo $color; ?>">

		<?php if(!isset($options["disable-ttr"])) { ?>
			<div class="sbprogress-container"><span class="sbprogress-bar"></span></div>
		<?php } ?>

		<div id="sb_main_bar">

			<div class="sb_text-size">
				<?php if(is_singular( 'post' )) { ?>
					<a href="<?php echo get_category_link($category[0]->cat_ID); ?>"><?php echo $category[0]->cat_name; ?></a>
				<?php } else { ?>
					<span><?php echo $post_type->label; ?></span>
				<?php } ?>
			</div>

			<div class="sb_post-data">
				<h2><?php the_title(); ?></h2>
				<?php if(!isset($options["disable-author"])) { ?>
					<span class="sb_author"><?php echo $by_text; ?> <?php echo $author_name; ?></span>
				<?php } ?>
				<?php if(!isset($options["disable-ttr"])) { ?>
				<span class="sb_ttr"><?php echo $ttr_text; ?> <?php echo $ttr; ?> min</span>
				<?php } ?>
			</div>

			<div class="sb_prev-next-posts">
				<?php $next_post = get_adjacent_post( $adjacent, '', false, $tag_or_cat ); ?>
				<?php if ( is_a( $next_post, 'WP_Post' ) ) { ?>
					<a href="<?php echo get_permalink( $next_post->ID ); ?>"><i class="sbicon-right-open-1"></i></a>
					<div class="sb_next_post">
						<div class="sb_next_post_image">
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id($next_post->ID), 'sb_image_size' ); ?>
							<?php if ($image != '') { ?> <img src="<?php echo $image[0]; ?>" alt=""> <?php } ?>
						</div>
						<div class="sb_next_post_info">
							<span class="sb_title">
								<span class="sb_category">
									<?php if(is_singular( 'post' )) {
											$category = get_the_category($next_post->ID); echo $category[0]->cat_name;
										} ?>
								</span>
								<span class="sb_tcategory">
									<?php
										$post_title = substr(get_the_title( $next_post->ID ),0,50);
										echo $post_title;
										if (strlen($post_title) >48){
											echo '…';
										}
									?>
								</span>
							</span>
						</div>
					</div>
				<?php } ?>
				<?php $prev_post = get_adjacent_post( $adjacent, '', true, $tag_or_cat ); ?>
				<?php if ( is_a( $prev_post, 'WP_Post' ) ) { ?>
					<a href="<?php echo get_permalink( $prev_post->ID ); ?>"><i class="sbicon-left-open-1"></i></a>
					<div class="sb_next_post">
						<div class="sb_next_post_image">
							<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id($prev_post->ID), 'sb_image_size' ); ?>
							<?php if ($image != '') { ?> <img src="<?php echo $image[0]; ?>" alt=""> <?php } ?>
						</div>
						<div class="sb_next_post_info">
							<span class="sb_title">
								<span class="sb_category">
									<?php if(is_singular( 'post' )) {
											$category = get_the_category($prev_post->ID); echo $category[0]->cat_name;
										} ?>
								</span>
								<span class="sb_tcategory">
									<?php
										$post_title = substr(get_the_title( $prev_post->ID ),0,50);
										echo $post_title;
										if (strlen($post_title) >48){
											echo '…';
										}
									?>
								</span>
							</span>
						</div>
					</div>
				<?php } ?>
			</div>

			<?php if(!isset($options["disable-share"])) { ?>
			<ul class="sb_share">
			    <li class="sbfacebook"><a href="#" title="Share on Facebook" class="sbsoc-fb" target="_blank"><i class="sbicon-facebook"></i><span>Share on Facebook</span></a></li>
			    <li class="sbtwitter"><a href="#" data-title="<?php the_title(); ?>" title="Share on Twitter" class="sbsoc-tw" target="_blank" ><i class="sbicon-twitter"></i><span>Share on Twitter</span></a></li>
			    <li class="sbgoogle-plus"><a href="#" title="Share on Google Plus" class="sbsoc-gplus" target="_blank"><i class="sbicon-gplus"></i><span>Share on Google Plus</span></a></li>
			    <li class="sblinkedin"><a href="#" title="Share on Linkedin" class="sbsoc-linked" target="_blank"><i class="sbicon-linkedin"></i><span>Share on LinkedIn</span></a></li>
    			<li class="sbpinterest"><a href="#" title="Share on Pinterest" class="sbsoc-pint" target="_blank"><i class="sbicon-pinterest"></i><span>Share on Pinterest</span></a></li>
			</ul>
			<?php } ?>

			<?php if(!isset($options["disable-comments"])) { ?>
			<div class="sb_actions">
				<a class="sb_comment" href="#<?php echo $commentsID; ?>"><?php echo $comment_count; ?><i class="sbicon-comment"></i></a>
			</div>
			<?php } ?>

		</div>

	</div>

<?php }
?>
