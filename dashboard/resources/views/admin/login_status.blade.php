<div>
	Numar observatori care s-au logat: {{ $loginCount }} din {{ $observersCount }}
	<form method="POST" action="<?php echo route($userType.'.sections.export_by_login_status'); ?>">
		{{ csrf_field() }}

		<input type="hidden" name="logged_in_status" value="NOT_LOGGED_IN"  />
		<input type="submit" value="Export sectii fara login" />
	</form>

	<form method="POST" action="<?php echo route($userType.'.sections.export_by_login_status'); ?>">
		{{ csrf_field() }}

		<input type="hidden" name="logged_in_status" value="LOGGED_IN" />
		<input type="submit" value="Export sectii cu login" />
	</form>
</div>