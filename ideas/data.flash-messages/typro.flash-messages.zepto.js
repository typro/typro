/** .typro Flash messages - JS helper for Zepto.js
 * 
 * @author		Jan Pecha, <janpecha@email.cz>
 * @license		http://typro.iunas.cz/license
 * @link		http://typro.iunas.cz/
 * @version		2012.06.03-1
 */
 
Zepto(function($) {
	$('.flash-message .flash-message-close,'
		+ '.flash-message-ok .flash-message-close,'
		+ '.flash-message-info .flash-message-close,'
		+ '.flash-message-important .flash-message-close,'
		+ '.flash-message-error .flash-message-close'
	).on('click', function() {
		$(this).parent()
			.css('overflow', 'hidden')
			// slideUp imitation
			.animate({
				opacity: 0,
				height: 0,
				minHeight: 0,
				margin: 0,
				paddingTop: 0,
				paddingBottom: 0,
			}, 300, 'ease-out', function() {
				$(this).remove();
			});
		
		return false;
	});
});
