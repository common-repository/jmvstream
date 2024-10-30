<?php

namespace Jmvstream\Includes\Admin;

?>

<div id="wpbody" role="main">
	<div id="wpbody-content">
		<div id="jmvstream__wrap" class="wrap">
			<div id="jmvstream__messages">
			</div>
			<?php if (!$this->_options['jmvstream-email'] || !$this->_options['jmvstream-password'] || !$this->_options['jmvstream-resource']) : ?>
				<div id="message" class="error notice is-dismissible">
					<p><?php esc_html_e('To use the plugin, you must first configure its API.', 'jmvstream') ?> <a href="admin.php?page=jmvstream-api-settings"><?php esc_html_e('Click here to configure.', 'jmvstream') ?></a></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php esc_html_e('Dismiss notice', 'jmvstream') ?></span>
					</button>
				</div>
			<?php endif; ?>
			<h1 class="wp-heading-inline">Jmvstream</h1>
			<a href="https://hub.jmvtechnology.com/#/home" target="_blank" class="page-title-action"><?php esc_html_e('Add Videos', 'jmvstream') ?></a>
			<a href="<?php esc_html_e("https://jmvstream.com/en/video-hosting-platform/#hosting-video-plans-pricing", "jmvstream") ?> " target="_blank" class="page-title-action"><?php esc_html_e('Plan Upgrade', 'jmvstream') ?></a>
			<hr class="wp-header-end">
			<h2 class="screen-reader-text"><?php esc_html_e('List Videos', 'jmvstream') ?></h2>
			<div class="wp-filter">
				<form method="post">
					<div class="jmvstream__filter-container">
						<div class="jmvstream__filter-item">
							<label for="jmvstream__initial-date" class=""><?php esc_html_e('Initial Date', 'jmvstream') ?></label>
							<input type="date" format="dd/mm/yyyy" id="jmvstream__initial-date" name="jmvstream__initial-date" value="" placeholder="dd/mm/yyyy" />
						</div>
						<div class="jmvstream__filter-item">
							<label for="jmvstream__end-date" class=""><?php esc_html_e('End Date', 'jmvstream') ?></label>
							<input type="date" format="dd/mm/yyyy" id="jmvstream__end-date" name="jmvstream__end-date" value="" placeholder="dd/mm/yyyy" />
						</div>
					</div>
				</form>
				<form method="post">
					<div class="jmvstream__filter-container jmvstream__search-form" id="search-videos">
						<div class="jmvstream__filter-item">
							<input id="jmvstream__filter-by-title" placeholder="<?php esc_html_e('Search...', 'jmvstream') ?>" value="" type="text" />
						</div>

						<div class="jmvstream__filter-item">
							<label for="attachment-filter" class="screen-reader-text"><?php esc_html_e('Filter by gallery', 'jmvstream') ?></label>
							<select id="jmvstream__filter-by-gallery" class="attachment-filters" name="jmvstream__filter-by-gallery">
								<!-- LISTAR LISTAR GALERIAS -->
							</select>
						</div>
					</div>
				</form>
			</div>
			<table class="wp-list-table widefat striped table-view-list media">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-title jmvstream__column-video sortable jmvstream__col-title">
							<a id="jmvstream__sort-by-title" sort=""><span><?php esc_html_e('Video', 'jmvstream') ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" class="manage-column jmvstream__column-duration">
							<a>
								<span><?php esc_html_e('Duration', 'jmvstream') ?></span>
							</a>
						</th>
						<th scope="col" class="manage-column jmvstream__column-shortcode">
							<a id="shortcode"><span>Shortcode</span></a>
						</th>
						<th scope="col" class="manage-column jmvstream__column-date sortable">
							<a id="jmvstream__sort-by-date" sort=""><span><?php esc_html_e('Date', 'jmvstream') ?></span><span class="sorting-indicator"></span></a>
						</th>
						<th scope="col" class="manage-column jmvstream__column-action">
							<a>
								<span><?php esc_html_e('Action', 'jmvstream') ?></span>
							</a>
						</th>
					</tr>
				</thead>
				<tbody id="the-list" class="jmvstream__the-list">
					<tr>
						<td colspan=4>
							<div class="lds-ellipsis">
								<div></div>
								<div></div>
								<div></div>
								<div></div>
							</div>
						</td>
					</tr>
					<!-- LISTA DOS VIDEOS -->
				</tbody>
			</table>
			<div id="jmvstream__paginator" class="tablenav bottom">
				<div class="jmvstream__tablenav-pages">
					<div class="jmvstream__displaying-num">
						<span></span>
					</div>
					<div class="jmvstream__pagination">
						<span id="jmvstream__prev-page" class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
						<span class="screen-reader-text"><?php esc_html_e('Current Page', 'jmvstream') ?></span>
						<span id="jmvstream__table-paging" class="paging-input">
							<span class="jmvstream__current-page tablenav-paging-text"></span>
						</span>
						<span id="jmvstream__next-page" class="tablenav-pages-navspan button" aria-hidden="true">›</span></a>
					</div>
				</div>

				<br class="clear">
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>