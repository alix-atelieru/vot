<form method="POST" action="{{ route('observer.login') }}">
	<input type="hidden" name="phone" value="0746091543" />
	<input type="hidden" name="pin" value="3232" />
	<input type="submit" value="Login" />
</form>

<form method="POST" action="{{ route('observer.add_section_count') }}">
	<input type="hidden" name="observer_id" value="1" />
	<input type="hidden" name="token" value="isjofcekaadvvlpaiecn" />
	
	<input type="hidden" name="psd_votes" value="700" />
	
	<input type="hidden" name="pnl_votes" value="300" />
	
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
	
	<input type="hidden" name="a" value="5" />
	<input type="hidden" name="a1" value="20" />
	<input type="hidden" name="a2" value="30" />
	<input type="hidden" name="b" value="40" />
	<input type="hidden" name="b1" value="50" />
	<input type="hidden" name="b2" value="60" />
	<input type="hidden" name="b3" value="70" />
	<input type="hidden" name="c" value="80" />
	<input type="hidden" name="d" value="90" />
	<input type="hidden" name="e" value="100" />
	<input type="hidden" name="f" value="110" />

	<input type="submit" value="Adauga voturi sectie" />
</form>


<form method="POST" action="{{ route('observer.quiz.answer') }}">
	<input type="hidden" name="observer_id" value="18742" />
	<input type="hidden" name="token" value="oxnhnjzrkfzwltkjnggx" />
	
	<!--
	<input type="hidden" name="question_id[]" value="1" />
	<input type="hidden" name="question_id[]" value="3" />
	<input type="hidden" name="question_id[]" value="2" />
	

	<input type="hidden" name="answer[]" value="nu" />
	<input type="hidden" name="answer[]" value="da" />
	<input type="hidden" name="answer[]" value="da" />
	-->

	<input type="submit" value="Raspunde la quiz" />
</form>


<form method="POST" action="{{ route('observer.section.select') }}">
	<input type="hidden" name="observer_id" value="3" />
	<input type="hidden" name="token" value="gipskgqmkmgqitmaveby" />
	
	<input type="hidden" name="section_id" value="2" />
	
	<input type="submit" value="Selecteaza sectie" />
</form>


<form method="POST" action="{{ route('observer.ref.save') }}">
	<input type="hidden" name="observer_id" value="4" />
	<input type="hidden" name="token" value="xvytvoonugnnfwcqscrd" />
	<input type="hidden" name="nr" value="1" />
	<?php for($i = 1;$i <= 10;$i++) {?>
		<input type="hidden" name="ref1_<?php echo $i ?>" value="<?php echo $i*10; ?>" />
	<?php } ?>

	<input type="submit" value="Salveaza intrebari ref1" />
</form>


<form method="POST" action="{{ route('observer.ref.save') }}">
	<input type="hidden" name="observer_id" value="4" />
	<input type="hidden" name="token" value="xvytvoonugnnfwcqscrd" />
	<input type="hidden" name="nr" value="2" />

	<?php for($i = 1;$i <= 10;$i++) { ?>
		<input type="hidden" name="ref2_<?php echo $i; ?>" value="<?php echo $i*100; ?>" />
	<?php } ?>
	<input type="hidden" name="ref2_11" value="440" />

	<input type="submit" value="Salveaza intrebari ref2" />
</form>


<form method="POST" action="https://sms.kendalo.ro/api_intl.php">
	<input type="hidden" name="api_user" value="mircea.serdin@usr.ro" />
	<input type="hidden" name="api_key" value="5cd51d9e024195cd51d9e0246c5cd51d9e024d9" />
	<input type="hidden" name="comanda" value="trimite_sms" />
	<input type="hidden" name="sender" value="1836" />
	
	<input type="hidden" name="nr" value="40768340418" />
	
	<!--
	<input type="hidden" name="nr" value="bnfjhdjd" />
	-->
	<input type="hidden" name="mesaj" value="hello world" />

	<input type="submit" value="trimite sms" />
</form>


<form method="POST" action="https://sms.kendalo.ro/api_intl.php">
	<input type="hidden" name="api_user" value="mircea.serdin@usr.ro" />
	<input type="hidden" name="api_key" value="5cd51d9e024195cd51d9e0246c5cd51d9e024d9" />
	<input type="hidden" name="comanda" value="verifica_status_sms" />
	<input type="text" name="id_mesaj" value="1789486" />

	<input type="submit" value="Verifica sms" />
</form>


<form method="GET" action="<?php echo route('observer.sms.send'); ?>">
	<input type="hidden" name="message" value="blabla" />
	<input type="hidden" name="phone" value="40768340418" />

	<input type="submit" value="Trmite sms obs." />
</form>

<form method="GET" action="<?php echo route('observer.votes'); ?>">
	<input type="hidden" name="observer_id" value="1" />
	<input type="hidden" name="token" value="isjofcekaadvvlpaiecn" />

	<input type="submit" value="Ia campuri" />
</form>