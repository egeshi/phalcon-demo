/**
 * XHR request module
 * @type {{send}}
 */
var xhr = (function (url) {

	/**
	 * Request parameters
	 * @type {{url: *, method: string, data: null, dataType: string, type: string, error: _params.error, success: _params.success, set: _params.set, get: _params.get}}
	 * @private
	 */
	var _params = {
		url: url,
		method: "OPTIONS",
		data: null,
		dataType: "json",
		type: "api",
		/**
		 *
		 * @param jqXHR
		 */
		error: function (jqXHR) {
			console.log(arguments);
			console.error(jqXHR);
		},
		/**
		 *
		 * @param response
		 */
		success: function (response) {
			console.info(response);
		},
		/**
		 *
		 * @param attr
		 * @param val
		 */
		set: function (attr, val) {
			this[attr] = val;
		},
		/**
		 *
		 * @param attr
		 * @returns {*}
		 */
		get: function (attr) {
			return this[attr];
		},
		element: null
	};

	/**
	 * Make single request or sequence
	 * @private
	 */
	var _request = function () {

		$.ajax(_params).then(function (response, textStatus) {

			console.log(typeof response);

			if (typeof response === 'object' && response.length === 0) {
				_params.set("data", _params.get("element").serialize());
				_params.set("method", "POST");
				$.ajax(_params);
			}
		});
	};

	var _setMethod = function () {
		if (_params['type'] === "post") {
			_params.set("method", "POST");
		} else {
			_params.set("method", "OPTIONS");
			_params.set("data", null);
		}
	}

	var getMethod = function () {

	}

	return {
		/**
		 * Class facade
		 * @param args
		 */
		send: function (args) {

			for (var p in args) {
				_params.set(p, args[p]);
			}
			
			_setMethod();
			_request();

		}
	}
}());
