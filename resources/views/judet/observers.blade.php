@include('judet/header')
<form method="GET">
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
	<input type="submit" value="Submit" />
</form>


@include("admin/jump_to_page")
@include('admin/login_status')
@include('admin/observers')