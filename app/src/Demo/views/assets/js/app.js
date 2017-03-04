/**
 * Created by takeshi on 28.02.2017.
 */
"use strict";

$("#loginButton").click(function () {

	var form = $("form");
	var type = "api";
	var dialogId = "loginDialog";

	if (!form.attr("action").match(/\/api\//)) {
		type = "post";
	}

	xhr.send({
		url: form.attr("action"),
		type: type,
		form: form,
		dialogId: dialogId,
		outputTo: $('#alertsContainer'),
	}).then(function (resolved) {
		switch (resolved.status) {
			case 301:
				//debugger;
				break;
			case 401:
				var type = "warning";
				var modalId = type + "Modal";
				_showModal("You are not registered", type, modalId, "Wrong credentials");
				break;
			default:
				console.error(resolved);
		}
	});

});

/**
 * Display alert
 *
 * @param message String
 * @param type String
 * @private
 */
var _showModal = function (message, type, id, headerText) {
	var modal = dialog.attach($('body'), {
		id: id || "xhrModal",
		headerText: headerText || "",
		bodyHtml: "<p>" + message + "</p>"
	}).show();
	return modal;
}
