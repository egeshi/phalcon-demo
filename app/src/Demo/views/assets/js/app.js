/**
 * Created by takeshi on 28.02.2017.
 */


$("#loginButton").click(function(){

	var form = $("form");
	var type = "api";

	if(!form.attr("action").match(/\/api\//)){
		type = "post";
	}

	xhr.send({
		url: form.attr("action"),
		type: type,
		element: form
	});
});
