@if ($request->status == 'done')
	done - <a href="{{ action('RequestController@download', $request->id) }}">download</a>
@elseif ($request->status == 'in_progress')
	in progress - <a href="{{ action('RequestController@cancel', $request->id) }}">cancel</a>
@endif