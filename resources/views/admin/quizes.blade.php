<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<th>
			Telefon
		</th>
		<th>
			Nume
		</th>

		<th>
			Data/ora
		</th>

		<th>
			Quiz
		</th>
	</thead>

	<tbody>
		@foreach($observers as $observer)
			<tr>
				<td>
					{{ $observer->phone }}
				</td>
				<td>
					{{ $observer->family_name }} {{ $observer->given_name }}
				</td>
				<td>
					{{ $observer->quiz_last_updated_datetime }}
				</td>

				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_{{ $observer->id }}">
	                    Vezi
	                </button>
	                
					<!-- Modal QUIZ-->
	                <div 
	                class="modal fade" 
	                id="modal_{{ $observer->id }}" 
	                tabindex="-1" 
	                role="dialog" 
	                aria-labelledby="exampleModalLabel" 
	                aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="exampleModalLabel">Quiz</h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                    <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                                <div class="list-group">
                            	     	<?php
										for ($i = 0;$i < count($observer->answers);$i++) {
							            ?>
		                                    <a href="#" 
		                                    class="list-group-item list-group-item-action flex-column align-items-start ">
			                                    <div class="d-flex w-100 justify-content-between">
			                                        <h5 class="mb-1"><strong>Intrebare:</strong> <?php echo $questions[$i]->content; ?></h5>
			                                       
			                                    </div>
			                                    <p class="mb-1">
			                                         <small><strong>Raspuns:</strong> <?php echo $observer->answers[$i]->answer; ?></small>
			                                    </p>
		                                    </a>
                                        <?php
										}
							            ?>
	                                </div>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
	                                    Inchide
	                                </button>
	                            </div>
	                        </div>
	                    </div>
	                </div><!--END MODAL QUIZ-->
	                
	                
				</td>
			</tr>
		@endforeach
	</tbody>
</table>