
<?php
if (is_user_logged_in() && function_exists('get_ratings_summary')) :
	$summary = get_ratings_summary(); ?>
	<div class="star-ratings">
		<span>Average Rating: <span class="average-rating"><?php echo $summary['average']; ?></span> |
		<span class="number-of-ratings"><?php echo $summary['number']; ?></span> votes |
		<span>Rate: </span><?php foreach ([1,2,3,4,5] as $star) : ?>
			<a href="#" class="star-rating" data-rating="<?php echo $star; ?>"><?php echo $star; ?></a>
		<?php endforeach; ?>
		<span class="waiting-ratings" style="display: none;"> Please wait...</span>
	</div>
<?php endif; ?>

<script>
	jQuery(document).ready(function($){
		var endpoint = '<?php echo home_url('/'); ?>wp-json/stars/rating';
		$('.star-ratings').on('click', '.star-rating', function(e) {
			e.preventDefault();
			$('.waiting-ratings').show();
			$.post({
				url: endpoint,
				data: {
					rating: $(this).data('rating'),
					post_id: <?php echo get_the_ID(); ?>,
					_wpnonce: '<?php echo wp_create_nonce('wp_rest'); ?>'
				},
				complete: function(xhr) {
					$.get({
						url: endpoint,
						data: {
							post_id: <?php echo get_the_ID(); ?>
						},
						success: function(response) {
							$('.average-rating').text(response.average);
							$('.number-of-ratings').text(response.number);
							$('.waiting-ratings').hide();
						}
					});
				}
			});
		});
	});
</script>
