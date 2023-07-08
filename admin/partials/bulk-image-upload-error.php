<?php
/**
 * This view being shown to display errors.
 *
 * @package bulk-image-upload-for-woocommerce
 */

?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<h2 style="color: #b32d2e"><?php esc_html_e( 'Something Went Wrong', 'bulk-image-upload' ); ?></h2>

		<div class="notice notice-error biu-notice">
			<?php echo esc_html( $args['error'] ); ?>
		</div>

		<div class="biu-mt-20">
			<a class="button button-primary"
			   href="<?php echo esc_url( get_admin_url( null, 'admin.php?page=bulk-image-upload' ) ); ?>">
				<?php esc_html_e( 'Go to Dashboard', 'bulk-image-upload' ); ?>
			</a>
		</div>

	</div>
</div>

<script>
    (function(d,t) {
        var BASE_URL="https://app.chatwoot.com";
        var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=BASE_URL+"/packs/js/sdk.js";
        g.defer = true;
        g.async = true;
        s.parentNode.insertBefore(g,s);
        g.onload=function(){
            window.chatwootSDK.run({
                websiteToken: 'zs5kEmpf18XhK4gkwEWPXLvv',
                baseUrl: BASE_URL
            })
        }
    })(document,"script");
</script>
