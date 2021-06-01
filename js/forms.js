(function () {
	LucyDom.onChildEvent(document, '.form__group--collapsible .form__group__label', 'click', function () {
		var parent = LucyDom.getClosest(this, '.form__group--collapsible');

		if (parent) {
			LucyDom.toggleClass(parent, 'form__group--collapsed');
		}
	});
})();
