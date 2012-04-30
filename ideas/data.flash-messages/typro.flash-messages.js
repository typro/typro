/** .typro Flash messages - JS helper
 * 
 * @author		Jan Pecha, <janpecha@email.cz>
 * @license		http://typro.iunas.cz/license
 * @link		http://typro.iunas.cz/
 * @version		2012.03.04-1
 */
 
$(document).ready(function(){
	$('.flash-message .flash-message-close,' +
		'.flash-message-ok .flash-message-close,' +
		'.flash-message-info .flash-message-close,' +
		'.flash-message-important .flash-message-close,' +
		'.flash-message-error .flash-message-close'
	).click(function(){
		$(this).parent().slideUp(300);
		return false;
	});
});
