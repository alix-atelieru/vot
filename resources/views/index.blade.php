<form method="POST" action="{{ route('observer.login') }}">
	<input type="hidden" name="phone" value="076" />
	<input type="hidden" name="pin" value="123" />
	<input type="submit" />
</form>

<form method="POST" action="{{ route('observer.add_section_count') }}">
	<input type="hidden" name="observer_id" value="1" />
	<input type="hidden" name="token" value="isjofcekaadvvlpaiecn" />
	
	<input type="hidden" name="psd_votes" value="70" />
	<input type="hidden" name="usr_votes" value="2" />
	<input type="hidden" name="alde_votes" value="3" />
	<input type="hidden" name="proromania_votes" value="4" />
	<input type="hidden" name="pmp_votes" value="5" />
	<input type="hidden" name="udmr_votes" value="" />
	<input type="hidden" name="other_votes" value="80008" />
	

	<input type="submit" value="Adauga voturi sectie" />
</form>
