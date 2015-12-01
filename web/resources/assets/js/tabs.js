function CurrentDataType()
{	
	/* Array of valid data types */
	var dataTypes = ['json', 'csv', 'xml'];

	/* Array used to hold which of valid data types are used for an example(counter) */
	var currentDataTypesByExample = [];

	/* Initialization of dataTypes for each example. Default dataType is json */
	(function () {
		$('div.example').each(function () {
			var counter = parseInt($(this).data('counter'), 10);
			if (counter >= 0) {
				currentDataTypesByExample[counter] = 'json';
			}
			throw "Invalid Example";
		});		
	}());

	/* Checks if dataType id valid */
	var validDataType = function (dataType) {
		return $.inArray(dataType, dataTypes) !== -1;
	};

	/* Checks if counter is valid */
	var validCounter = function (counter) {
		return counter >= 0 && counter < currentDataTypesByExample.length;
	};

	/* Returns current dataType used for example(counter). */
	this.getCurrentDataTypeByExample = function (counter) {
		var intCounter = parseInt(counter, 10);
		if (validCounter(intCounter)) {
			return currentDataTypesByExample[intCounter];
		}
		throw "Invalid Example";
	};

	/* Sets dataType used for example(counter). */
	this.setCurrentDataTypeByExample = function (counter, dataType) {
		var intCounter = parseInt(counter, 10);
		if (validDataType(dataType) && validCounter(intCounter)) {
			currentDataTypesByExample[intCounter] = dataType;
		}
		throw "Invalid Example Or DataType";	
	};
}

var typesHandler = new CurrentDataType();
$('.nav-tabs a').click(function(e) {
	e.preventDefault();
	$(this).tab('show');
});
