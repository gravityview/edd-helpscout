<?php defined( 'ABSPATH' ) or exit; ?>
<div class="toggleGroup">

	<?php do_action( 'github_helpscout_before_issue', $issue ); ?>

    <div style="margin-bottom: 10px">
	<strong>
		<a target="_blank" href="<?php echo esc_attr( $issue['url'] ); ?>"><?php echo esc_attr( $issue['repo'] . ' #' . $issue['id'] ); ?></a>
	</strong>
    </div>

	<?php do_action( 'github_helpscout_after_issue', $issue ); ?>

</div>

<div class="divider"></div>
