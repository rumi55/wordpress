<?php
/**
 * Search Form Template
 *
 *
 * @file           searchform.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Function_Reference/get_search_form
 * @since          available since Release 1.0
 */
?>
	<form method="get" class="form-search form-inline" action="<?php echo home_url( '/' ); ?>">
		<input type="text" class="input-small search-query" name="s" placeholder="<?php esc_attr_e('search here &hellip;', 'responsive'); ?>" />
		<button type="submit" class="btn" name="submit" id="searchsubmit" value="<?php esc_attr_e('Go', 'responsive'); ?>">Search</button>
	</form>