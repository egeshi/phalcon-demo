/*
 * Copyright (c) 2017. Antony Repin
 * Created by Antony Repin on 04.03.2017.
 */
"use strict";

/**
 *
 * @type {{attach, show}}
 */
var dialog = (function () {

	/**
	 *
	 * @type {{headerText: string, bodyHtml: string, footerHtml: string, id: string, set: _params.set, get: _params.get}}
	 * @private
	 */
	var _params = {
		headerText: "Default header",
		bodyHtml: "<p>Default body HTML</p>",
		footerHtml: null,
		id: "",
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
	}

	var _container;

	var _create = function () {
		var header = $('<div/>').addClass("modal-header")
				.append($('<h5/>')
						.text(_params.get("headerText")))
				.addClass("modal-title");

		var body = $('<div/>')
				.html(_params.get("bodyHtml"))
				.addClass("modal-body")

		var footer = $('<div/>')
				.html(_params.get("footerHtml"))
				.addClass("modal-footer");

		var footer = $('<button/>')
				.addClass("btn btn-secondary")
				.attr({"data-dismiss": "modal"})
				.text("Close");

		if (_params.get("footerHtml") !== null){
			footer = _params.get("footerHtml");
		}

		_container = $('<div/>').addClass("modal fade").attr({
			id: _params.get("id"),
			tabindex: "-1",
			role: "dialog",
			"aria-labelledby": _params.get("id") + "Label",
			"aria-hidden": true
		}).append($('<div/>').addClass("modal-dialog").attr({"role": "document"})
				.append($('<div/>').addClass("modal-content")
						.append(header)
						.append(body)
						.append(footer)))
				.appendTo($("body"));

	}

	return {
		attach: function (selector, args) {
			for (var p in args) {
				_params.set(p, args[p]);
			}
			_create();
			return this;
		},
		show: function () {
			_container.modal();
		}
	}

}());
