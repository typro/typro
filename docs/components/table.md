# table

```less
@import 'typro/06-components/table';
```

```html
<table class="table">
	<thead class="table__head">
		<tr class="table__row">
			<th class="table__cell table__cell--header">Column A</th>
			<th class="table__cell table__cell--header">Column B</th>
		</tr>
	</thead>

	<tbody class="table__body">
		<tr class="table__row">
			<td class="table__cell">Column A</td>
			<td class="table__cell">Column B</td>
		</tr>
	</tbody>

	<tfoot class="table__foot">
		<tr class="table__row">
			<td class="table__cell">Column A</td>
			<td class="table__cell">Column B</td>
		</tr>
	</tfoot>
</table>
```


## Modifiers

* **Table**
	* `.table--hover`
	* `.table--striped`
	* `.table--small`
	* `.table--xsmall`

* **Rows**
	* `.table__row--danger`
	* `.table__row--success`

* **Cells**
	* `.table__cell--danger`
	* `.table__cell--success`
