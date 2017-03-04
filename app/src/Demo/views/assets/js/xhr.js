"use strict";

/**
 * XHR request module
 * @type {{send}}
 */
var xhr = (function (url) {

	/**
	 *
	 * @type {{url: *, method: string, data: string, dataType: string, type: string, error: function, success: function, set: function, get:function, form: jQuery.fn, outputTo: jQuery.fn, modalId: string}}
	 * @private
	 */
	var _params = {
		url: url,
		method: "OPTIONS",
		data: null,
		dataType: "json",
		type: "api",
		/**
		 * @param jqXHR Object
		 */
		error: function (jqXHR) {
			$_def.resolve(jqXHR);
		},
		/**
		 * @param response Object
		 */
		success: function (response) {
			console.info(response);
		},
		/**
		 * @param attr String
		 * @param val mixed
		 */
		set: function (attr, val) {
			this[attr] = val;
		},
		/**
		 *
		 * @param attr String
		 * @returns {*}
		 */
		get: function (attr) {
			return this[attr];
		},
		form: null,
		outputTo: null, //jQuery.fn
		callback: null //function
	};

	/**
	 * User credentials container
	 */
	var _credentials;

	var $_def = $.Deferred();

	/**
	 * Make single request or sequence
	 *
	 * @private
	 */
	var _request = function () {

		_params.set("data", _params.get("form").serialize());

		$.ajax(_params).then(function (response, textStatus) {

			if (_params.get("method") == "OPTIONS") {
				if (typeof response === 'object' && response.length === 0) {

					_params.set("data", _params.get("form").serialize());
					_params.set("method", "POST");

					$.ajax(_params).then(function () {
						$_def.resolve(arguments);
					});

				}
			}

			$_def.resolve({
				data: arguments[0].data,
				textStatus: arguments[1],
				jqXHR: arguments[2]
			});

		});
	};

	/**
	 * Set request method
	 *
	 * @private
	 */
	var _setMethod = function () {
		if (_params['type'] === "post") {
			_params.set("method", "POST");
		} else {
			_params.set("method", "OPTIONS");
			_params.set("data", null);
		}
	}

	return {
		/**
		 * Class facade
		 *
		 * @param args
		 */
		send: function (args) {

			for (var p in args) {
				_params.set(p, args[p]);
			}

			_setMethod();

			//var $def = $.Deferred();

			_request();

			return $_def.promise();

		}
	}
}());
