<?php
/**
 * single-hello_live.php — YouTube LIVE 記事
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$yt_id    = hello_youtube_id( get_field( 'youtube_url' ) );
	$members  = get_field( 'members_only' );
	$live_date = get_field( 'live_date' );
	$chapters = get_field( 'chapters' );
	$qa       = get_field( 'live_qa' );
	hello_main_open( 'hello-live' );
	hello_lang_switcher();
	?>
	<header>
		<?php if ( $members ) : ?><span class="hello-badge -members">会員限定</span><?php endif; ?>
		<span class="hello-badge">YouTube LIVE</span>
		<h1 class="hello-mag__ttl"><?php the_title(); ?></h1>
		<div class="hello-meta">
			<?php if ( $live_date ) : ?><span>開催日: <?php echo esc_html( $live_date ); ?></span><?php endif; ?>
		</div>
		<?php hello_the_tags(); ?>
	</header>

	<?php if ( $yt_id ) : ?>
		<div class="hello-live__embed">
			<iframe id="hello-live-player"
				src="https://www.youtube.com/embed/<?php echo esc_attr( $yt_id ); ?>?rel=0"
				title="<?php the_title_attribute(); ?>"
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
				allowfullscreen></iframe>
		</div>
		<script>
		// チャプター/Q&Aの再生位置から再生（YouTube start パラメータ）
		// ※「該当秒数のサムネ表示」は方式未定（docs 03-questions Q5）。当面はジャンプ再生のみ。
		(function(){
			var id = <?php echo wp_json_encode( $yt_id ); ?>;
			document.addEventListener('click', function(e){
				var el = e.target.closest('[data-seek]');
				if(!el) return;
				e.preventDefault();
				var s = parseInt(el.getAttribute('data-seek'),10) || 0;
				var f = document.getElementById('hello-live-player');
				if(f){ f.src = 'https://www.youtube.com/embed/'+id+'?rel=0&autoplay=1&start='+s; window.scrollTo({top:f.getBoundingClientRect().top+window.scrollY-20,behavior:'smooth'}); }
			});
		})();
		</script>
	<?php endif; ?>

	<?php if ( get_the_content() ) : ?>
		<div class="post_content"><?php the_content(); ?></div>
	<?php endif; ?>

	<?php if ( $chapters ) : ?>
		<section class="hello-sec">
			<h2 class="hello-sec__ttl">目次</h2>
			<ul class="hello-chapters">
				<?php foreach ( $chapters as $c ) :
					$sec = hello_timestamp_to_seconds( $c['timestamp'] ?? '' ); ?>
					<li><button type="button" data-seek="<?php echo esc_attr( $sec ); ?>">
						<span class="t"><?php echo esc_html( $c['timestamp'] ?: '0:00' ); ?></span>
						<span><?php echo esc_html( $c['title'] ?? '' ); ?></span>
					</button></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<?php if ( $qa ) : ?>
		<section class="hello-sec">
			<h2 class="hello-sec__ttl">要約Q&A</h2>
			<div class="hello-qa">
				<?php foreach ( $qa as $row ) :
					$sec = hello_timestamp_to_seconds( $row['video_timestamp'] ?? '' ); ?>
					<div class="hello-qa__item">
						<p class="hello-qa__q">Q. <?php echo esc_html( $row['question'] ?? '' ); ?></p>
						<p class="hello-qa__a"><?php echo nl2br( esc_html( $row['answer_summary'] ?? '' ) ); ?></p>
						<?php if ( ! empty( $row['video_timestamp'] ) ) : ?>
							<button type="button" class="hello-qa__jump" data-seek="<?php echo esc_attr( $sec ); ?>">▶ 動画で見る（<?php echo esc_html( $row['video_timestamp'] ); ?>〜）</button>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	hello_main_close();
endwhile;
get_footer();
