@include('national/header')

<!-- poate punem si fara judet?-->

<form method="GET">
	Judet selectat la login:
	<select name="judet_id">
		
		<option 
		value="ALL" 
		<?php 
		if (empty($requestDict['judet_id'])) {
			echo ' selected ';
		} elseif ($requestDict['judet_id'] == 'ALL') {
			echo ' selected ';
		}
		?>>
			TOATE
		</option>

		<option 
		value="NOT_COMPLETED"
		<?php 
		if (!empty($requestDict['judet_id']) && $requestDict['judet_id'] == 'NOT_COMPLETED') {
			echo 'selected';
		} 
		?>
		>
			Judet necompletat
		</option>

		@foreach ($judete as $judet)
			<?php
			$selected = '';
			if (!empty($requestDict['judet_id']) && $judet->id == $requestDict['judet_id']) {
				$selected = ' selected="selected" ';
			}
			?>
			<option value="{{ $judet->id }}"<?php echo $selected; ?>>
				{{ $judet->name }}
			</option>
		@endforeach
	</select>
	<input type="submit" />
</form>


@include("admin/jump_to_page")

@include('admin/observers')