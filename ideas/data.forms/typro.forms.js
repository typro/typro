/** .typro Forms - Zepto.JS / jQuery helper
 * 
 * @author		Jan Pecha, <janpecha@email.cz>
 * @license		http://typro.iunas.cz/license
 * @link		http://typro.iunas.cz/
 * @version		2012-07-30-1
 */
 
$(function() {
	var defaultVal = $('input[type=color]')
		.attr('type', 'text')
		.attr('maxlength', 7)
		.wrap('<div></div>')
		.each(function(index, item) {
			// 'this' is input
			_this = $(this);
			
			var span = $('<span class="typro-input-after"></span>')
				.css({
					height: _this.css('height'),
					lineHeight: _this.css('line-height'),
					padding: _this.css('padding'),
					borderRadius: _this.css('border-radius'),
					border: _this.css('border')
				});
			
			_this.parent().addClass('typro-input-color');
			
			_this.on('blur', function(e) {
				var input = $(e.target);
				var val = input.val();
				
				if(val != '')
				{
					if(val.charAt(0) != '#')
					{
						val = '#' + val;
						input.val(val);
					}
					
					var span = input.siblings().first().css('background', val);
				}
			});
			
			_this.after(span);
		});
});
