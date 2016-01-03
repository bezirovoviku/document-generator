@if ($request->status == App\Request::STATUS_DONE)
	<span class="text-success">
		<span class="glyphicon glyphicon-ok"></span> {{ trans('partial.done') }}
	</span>
@elseif ($request->status == App\Request::STATUS_IN_PROGRESS)
	<span class="text-warning">
		<span class="glyphicon glyphicon-hourglass"></span> {{ trans('partial.inProgres') }}
	</span>
@elseif ($request->status == App\Request::STATUS_FAILED)
<span class="text-danger">
	<span class="glyphicon glyphicon-exclamation-sign"></span> {{ trans('partial.failed') }}
</span>
@endif
