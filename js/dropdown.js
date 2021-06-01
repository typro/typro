;(function(document) {

	LucyDom.onEvent(document, 'click', function (event) {
		var dropdowns = LucyDom.findAll(document, '.dropdown--active');

		for (var i = 0; i < dropdowns.length; i++) {
			LucyDom.removeClass(dropdowns[i], 'dropdown--active');
		}
	});

	LucyDom.onChildEvent(document, '.dropdown__button', 'click', function (event) {
		LucyDom.toggleClass(this.parentNode, 'dropdown--active');
	});

})(document);
