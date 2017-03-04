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
		form: null,
		outputTo: null, //jQuery.fn
		callback: null, //function
		token: null,
		/**
		 * @param jqXHR Object
		 */
		error: function (jqXHR) {

			switch (jqXHR.status) {

				case 500:
					console.error(jqXHR.statusText);
					dialog.attach($('body'), {
						id: "xhrSeverErrorModal",
						headerText: "Error",
						bodyHtml: "<p>" + jqXHR.statusText + "</p>"
					}).show();
					break;

				case 200:
				case 403:
					var json = jqXHR.responseJSON;
					var messages = "";

					for (var p in json.data) {
						messages += p + ": " + json.data[p];
					}

					dialog.attach($('body'), {
						id: "xhrErrorModal",
						headerText: "Error",
						bodyHtml: "<p>" + messages + "</p>"
					}).show();
					break;

				default:
					console.info(jqXHR);
			}


		},
		/**
		 * @param response Object
		 */
		success: function () {

			var response = arguments[0];

			if (response.hasOwnProperty("success")) {
				if (response.success === false) {
					var messages = "";

					for (var p in response.data) {
						messages += p + ": " + response.data[p];
					}

					dialog.attach($('body'), {
						id: "xhrErrorModal",
						headerText: "Error",
						bodyHtml: "<p>" + messages + "</p>"
					}).show();
					return;
				}

				if(response.data.hasOwnProperty("location")){
					window.location.href = response.data.location;
				}

			}

			$_def.resolve({
				success: response.success,
				data: response.data,
				textStatus: arguments[1],
				jqXHR: arguments[2]
			})
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
		beforeSend: function (request) {
			if (_params.get("token")) {
				request.setRequestHeader("Authorization", _params.get("token"));
			}
		}
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

					$.ajax(_params);

				}
			}
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
