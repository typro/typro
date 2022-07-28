# dropdown

Dropdown button.

```less
@import 'typro/06-components/dropdown';
```

```html
<div class="dropdown">
	<button class="dropdown__button">
		<span class="dropdown__button-label">Label</span>
	</button>

	<div class="dropdown__menu">
		<a href="#item1" class="dropdown__menu-item dropdown__menu-item--active">
			<span class="dropdown__menu-item-label">Item 1</span>
		</a>

		<a href="#item2" class="dropdown__menu-item">
			<span class="dropdown__menu-item-label">Item 2</span>
		</a>
	</div>
</div>
```


## Modifiers

* `.dropdown--small`
* `.dropdown--xsmall`
* `.dropdown--active` (open dropdown)


## JavaScript

* `js/dropdown.js` (requires `frontpack/lucy.js`)
