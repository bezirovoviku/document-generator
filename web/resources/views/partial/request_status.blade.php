@if ($request->status == 'done')
	<span class="text-success">
		<span class="glyphicon glyphicon-ok"></span> done
	</span>
@elseif ($request->status == 'in_progress')
	<span class="text-warning">
		<span class="glyphicon glyphicon-hourglass"></span> in progress
	</span>
@endif