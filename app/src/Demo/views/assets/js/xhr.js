/**
 * XHR request module
 * @type {{send}}
 */
var xhr = (function(url){

	var _params = {
		url: url,
		method: "GET",
		data: null,
		dataType: "json",
		error: function(jqXHR){
			console.error(jqXHR);
		},
		success: function(response){
			console.info(response);
		},
		set: function(attr, val) {
			this[attr] = val;
		},
		get: function(attr){
			return this[attr];
		}
	}
	var _request = function(){
		$.ajax(_params);
	}

	return {
		// Facade
		send: function(args){

			for (var p in args){
				_params.set(p, args[p]);
			}

			_request();

		}
	}
}());
