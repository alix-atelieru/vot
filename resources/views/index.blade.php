<form method="POST" action="{{ route('observer.login') }}">
	<input type="hidden" name="phone" value="058" />
	<input type="hidden" name="pin" value="4562" />
	<input type="submit" value="Login" />
</form>

<form method="POST" action="{{ route('observer.add_section_count') }}">
	<input type="hidden" name="observer_id" value="3" />
	<input type="hidden" name="token" value="gipskgqmkmgqitmaveby" />
	
	<input type="hidden" name="psd_votes" value="70" />
	<input type="hidden" name="usr_votes" value="2" />
	<input type="hidden" name="alde_votes" value="3" />
	<input type="hidden" name="proromania_votes" value="4" />
	<input type="hidden" name="pmp_votes" value="5" />
	<input type="hidden" name="udmr_votes" value="10" />

	<input type="hidden" name="prodemo_votes" value="10" />
	<input type="hidden" name="psr_votes" value="20" />
	<input type="hidden" name="psdi_votes" value="30" />
	<input type="hidden" name="pru_votes" value="40" />
	<input type="hidden" name="unpr_votes" value="50" />
	<input type="hidden" name="bun_votes" value="60" />
	<input type="hidden" name="tudoran_votes" value="70" />
	<input type="hidden" name="simion_votes" value="80" />
	<input type="hidden" name="costea_votes" value="90" />
	<input type="hidden" name="other_votes" value="0" />
	

	<input type="submit" value="Adauga voturi sectie" />
</form>


<form method="POST" action="{{ route('observer.quiz.answer') }}">
	<input type="hidden" name="observer_id" value="1" />
	<input type="hidden" name="token" value="isjofcekaadvvlpaiecn" />
	
	
	<input type="hidden" name="question_id[]" value="1" />
	<input type="hidden" name="question_id[]" value="3" />
	<input type="hidden" name="question_id[]" value="2" />
	

	<input type="hidden" name="answer[]" value="nu" />
	<input type="hidden" name="answer[]" value="da" />
	<input type="hidden" name="answer[]" value="da" />
	
	<input type="submit" value="Raspunde la quiz" />
</form>


<form method="POST" action="{{ route('observer.section.select') }}">
	<input type="hidden" name="observer_id" value="3" />
	<input type="hidden" name="token" value="gipskgqmkmgqitmaveby" />
	
	
	<input type="hidden" name="section_id" value="2" />
	
	
	<input type="submit" value="Selecteaza sectie" />
</form>