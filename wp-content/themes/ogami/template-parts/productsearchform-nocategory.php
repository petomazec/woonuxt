
<div class="apus-search-form search-fix clearfix">
	<div class="inner-search">
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<div class="main-search">
				<div class="autocompleate-wrapper">
			  		<input type="text" placeholder="<?php esc_attr_e( 'Search products here...', 'ogami' ); ?>" name="s" class="apus-search form-control apus-autocompleate-input" autocomplete="off"/>
				</div>
			</div>
			<input type="hidden" name="post_type" value="product" class="post_type" />
			<button type="submit" class="btn btn-theme radius-0"><i class="fa fa-search"></i></button>
		</form>
	</div>
</div>