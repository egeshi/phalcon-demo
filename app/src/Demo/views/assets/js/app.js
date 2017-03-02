/**
 * Created by takeshi on 28.02.2017.
 */

$("#loginButton").click(function(){
	var form = $("form");
	xhr.send({
		url: form.attr("action"),
		method: "POST",
		data: form.serialize()
	});
});
