var $f1 = function test(o1) {
	$("#hover-menu").css("display", "inline");
	return "OK";
}
var $f2 = function test2(o2) {
	$("#hover-menu").css("display", "none");
}

$(document).ready(function() {
	//$("li").hover($f1, $f2);
	$f1();
});
