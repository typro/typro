# forms

Responsive block image.

```less
@import 'typro/components/forms';
```

```html
<form class="form">
	<div class="form__controls">
		<div class="form__control-pair form__control-pair--required">
			<div class="form__label"><label for="frm-username" class="form__control--required">User Name</label></div>

			<div class="form__control">
				<input type="text" name="username" id="frm-username" class="form__control--text">
			</div>
		</div>

		<div class="form__control-pair">
			<div class="form__control"><input type="submit" name="save" value="Save" class="button"></div>
		</div>
	</div>
</form>
```


## Modifiers

* `.form--hidden`
* `.form--wide`
