function slideSwitch(){var t=$("#slideshow IMG.active")
0==t.length&&(t=$("#slideshow IMG:last"))
var e=t.next().length?t.next():$("#slideshow IMG:first")
t.addClass("last-active"),e.css({opacity:0}).addClass("active").animate({opacity:1},2e3,function(){t.removeClass("active last-active")})}$(function(){setInterval("slideSwitch()",5e3)})