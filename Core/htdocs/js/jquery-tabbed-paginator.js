/**
 * Created by Perspective Ltd.
 * User: Richard Elmes
 * Date: 27/06/12
 * Time: 15:48
 * ---
 * OVERRIDE FUNCTIONS TO ALLOW TABS ON PAGINATED RESULT SETS
 * WHEN USING THE YUI TAB MECHANISM
 * #TODO - reload time seems slow...
 */

$(document).ready(function () {

	// ----
	// removes the active tab / visible content when clicking a tab
	// ----
	$("a[href^=#tab]").click( function() {
		$("a[href^=#tab]").parent().attr('class', '');
		$("a[href^=#tab]").parent().attr('title', '');
		var $link_href = $(this).attr('href').replace("#",'');
		$("div[id^='tab']").hide();
		$("div[id='"+$link_href+"']").show();
	});

	// ----
	// adds the active tab id to the onclick event for all buttons ( only buttons at mo )
	// on the page.
	// ----
	var $activated_tab = 0;
	$("div[id^='tab']").each(function() {
		var $tab_id = $(this).attr('id');
		$(this).find(":button").each( function() {
			if ( $(this).attr('onclick') !== undefined ) {
				var $link_href = $(this).attr('onclick').match(/^(.*)window.location.href='(.*)'(.*)/i);
				var $tabbed_link = $link_href[1]+"window.location.href='"+$link_href[2]+"&active="+$tab_id+"'"+$link_href[3];
				$(this).attr('onclick', $tabbed_link);
			}
		});

		if ( $activated_tab === 0 ) {
			var $active_tab_link = $(location).attr('href').match(/^(.*)active=(.*)$/i);
			if ( $active_tab_link !== undefined && $active_tab_link !== null ) {
				$("div[id^='tab']").hide();
				$("div[id='"+$active_tab_link[2]+"']").show();
				$("a[href=#"+$active_tab_link[2]+"]").parent().attr('class', 'selected');
				$("a[href=#"+$active_tab_link[2]+"]").parent().attr('title', 'active');
			}
			$activated_tab = 1;
		}
	});
// ----
});
