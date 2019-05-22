@include('national/header')

<!-- poate punem si fara judet?-->

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

	Nu au:
	<select name="activity">
		<option 
		value="ALL"
		<?php if (empty($requestDict['activity']) || $requestDict['activity'] == 'ALL')  echo 'selected'?>
		>
			-----
		</option>

		<option 
		value="NO_ELECTIONS_RESULTS"
		<?php if (!empty($requestDict['activity']) && $requestDict['activity'] == 'NO_ELECTIONS_RESULTS')  echo 'selected'?>
		>
			completat rezultate alegeri
		</option>

		<option value="NO_REF1"
		<?php if (!empty($requestDict['activity']) && $requestDict['activity'] == 'NO_REF1')  echo 'selected'?>
		>
			completat formular referendum 1
		</option>

		<option value="NO_REF2"
		<?php if (!empty($requestDict['activity']) && $requestDict['activity'] == 'NO_REF2')  echo 'selected'?>
		>
			completat formular referendum 2
		</option>

	</select>

	<input type="submit" />
</form>


@include("admin/jump_to_page")
@include('admin/login_status')
@include('admin/observers')