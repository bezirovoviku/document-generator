$(document).on('change', '.btn-file :file', function() {
	var input = $(this);
	var numFiles = input.get(0).files ? input.get(0).files.length : 1;
	var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

	input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
	$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
		var input = $(this).parents('.input-group').find(':text');
		input.val(label);
	});
});
function showRequestDataType(dataType, requestType, counter)
{
	var request = $('#' + requestType + '-' + dataType + '-' + counter);
		request.show();
	return request;
}
function hideOtherRequestDataTypes(showedRequest)
{
	showedRequest.siblings('pre').hide();
}

function disableOtherButtons(enabledButton, otherButtons)
{
	otherButtons.removeClass('active');
}
			/* Manages data types and requests for each example */
function RequestDataTypesHandler()
{	
	/* Array of valid data types */
	var dataTypes = ['json', 'csv', 'xml'];

	/* Array of valid request types */
	var requestTypes = ['data', 'full'];

	/* Array used to hold which of valid data type and request type is used for an example(counter) */
	var currentRequestDataTypesByExample = [];

	/* Initialization of dataTypes for each example. Default dataType is json */
	(function () {
		$('div.example').each(function () {
			var counter = parseInt($(this).data('counter'), 10);
			if (counter >= 0) {
				currentRequestDataTypesByExample[counter] = {requestType: 'full', dataType: 'json'};
				hideOtherRequestDataTypes(showRequestDataType('json', 'full', counter));
			} else {
				throw "Invalid Example";
			}
		});		
	}());

	/* Checks if dataType id valid */
	var validDataType = function (dataType) {
		return $.inArray(dataType, dataTypes) !== -1;
	};

	/* Checks if requestType id valid */
	var validRequestType = function (requestType) {
		return $.inArray(requestType, requestTypes) !== -1;
	};

	/* Checks if counter is valid */
	var validCounter = function (counter) {
		return counter >= 0 && counter < currentRequestDataTypesByExample.length;
	};

	/* Returns object with current data type and request type used for example(counter). */
	this.getCurrentRequestDataTypeByExample = function (counter) {
		var intCounter = parseInt(counter, 10);
		if (validCounter(intCounter)) {
			return currentRequestDataTypesByExample[intCounter];
		}
		throw "Invalid Example";
	};

	/* Sets dataType used for example(counter). */
	this.setCurrentDataTypeByExample = function (counter, dataType) {
		var intCounter = parseInt(counter, 10);
		if (validDataType(dataType) && validCounter(intCounter)) {
			currentRequestDataTypesByExample[intCounter].dataType = dataType;
		} else {
			throw "Invalid Example Or DataType";
		}
	};

	/* Sets dataType used for example(counter). */
	this.setCurrentRequestTypeByExample = function (counter, requestType) {
		var intCounter = parseInt(counter, 10);
		if (validRequestType(requestType) && validCounter(intCounter)) {
			currentRequestDataTypesByExample[intCounter].requestType = requestType;
		} else {
			throw "Invalid Example Or RequestType";
		}
	};
}
var requestDataTypesHandler = new RequestDataTypesHandler();
$('div.example button.data-type').click(function () {
	var $this = $(this);
	var counter = $this.parent().data('counter');
	var dataType = $this.data('datatype');
	var requestType = requestDataTypesHandler.getCurrentRequestDataTypeByExample(counter).requestType;
	requestDataTypesHandler.setCurrentDataTypeByExample(counter, dataType);
	hideOtherRequestDataTypes(showRequestDataType(dataType, requestType, counter));
	$this.addClass('active');
	disableOtherButtons($this, $this.siblings('button.data-type'));
});
$('div.example button.request-type').click(function () {
	var $this = $(this);
	var counter = $this.parent().data('counter');
	var requestType = $this.data('requesttype');
	var dataType = requestDataTypesHandler.getCurrentRequestDataTypeByExample(counter).dataType;
	requestDataTypesHandler.setCurrentRequestTypeByExample(counter, requestType);
	hideOtherRequestDataTypes(showRequestDataType(dataType, requestType, counter));
	$this.addClass('active');
	disableOtherButtons($this, $this.siblings('button.request-type'));
});
$('.nav-tabs a').click(function(e) {
	e.preventDefault();
	$(this).tab('show');
});
//# sourceMappingURL=app.js.map
