/** .typro Hover menu - jQuery helper
 * 
 * @author		Jan Pecha, <janpecha@email.cz>
 * @license		http://typro.iunas.cz/license
 * @link		http://typro.iunas.cz/
 * @version		2012-07-17-1
 */
 
$(document).ready(function() {
	$('.hover-menu').addClass('hover-menu-no-hover');

	$('.hover-menu-sub').click(function() {
		var isButtonActive = $(this).hasClass('hover-menu-active');

		$('.hover-menu-sub').removeClass('hover-menu-active');

		if(!isButtonActive)
		{
			$(this).addClass('hover-menu-active');//.addClass('hover-menu-active');
		}
		
		return false;
	});
}).click(function() {
	$('.hover-menu-sub').removeClass('hover-menu-active');
});
