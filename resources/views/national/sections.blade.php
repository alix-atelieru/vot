@include('national/header')

<form method="GET">
	Judet:
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

@include('admin/sections')