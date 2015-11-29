@extends('layout.master')
@section('title', 'Documentation')

@section('content')
<div class="col-md-2">
	<div class="accordion navbar navbar-default" role="navigation" id="leftMenu">
        <div class="accordion-group">
            <div class="accordion-heading">
            	<a class="accordion-toggle" data-toggle="collapse" data-parent="#leftMenu" href="#">
                    API
                </a>
            </div>
        </div>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#leftMenu" href="#collapseTwo">
                    TEMPLATES
                </a>
            </div>
            <div id="collapseTwo" class="accordion-body collapse" style="height: 0px; ">
            	<div class="accordion-inner">
                    <ul>
                        <li><a href="#">This is one</a></li>
                        <li><a href="#">This is one</a></li>
                        <li><a href="#">This is one</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#leftMenu" href="#collapseThree">
                    EXAMPLES
                </a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-10">
<h1 class="page-header" id="api">Api</h1>

<p>For API documentation, please refer to our <a href="http://docs.docgen.apiary.io">Apiary docs</a></p>
</div>
@endsection
@section('footer')
	<div id="footer" class="col-md-10 pull-right">
		© {{ Config::get('app.name') }} {{ date('Y') }}
	</div>
@overwrite
@section('custom_scripts')
	<script type="text/javascript">
		function active_links(links, activeClass, activeColor, originalColor)
		{
			links.click(function (e) {
				var that = $(this);
				that.parent().addClass(activeClass);
				that.css('color', activeColor);
				links.each(function () {
					var thatt = $(this);
					if (!thatt.is(that)) {
						thatt.parent().removeClass(activeClass);
						thatt.css('color', originalColor);
					}
				});
			});
		}
		$(document).ready(function () {
			active_links($('.accordion-heading a'), 'active_heading', 'white', '#ECF0F1');
			active_links($('#collapseTwo ul a'), 'active_link', 'white', '#2C3E50');
		});
</script>
@endsection