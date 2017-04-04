var loadWait = function(){
	$('body').waitMe({
		effect : 'rotation',
		text: 'Loading....',
		bg : 'rgba(255,255,255,0.7)',
		color : '#0D47A1',
		maxSize : '500',
		textPos : 'vertical',
		fontSize : '20px',
		source : ''
	});
};

var stopWait = function(){
	$("body").waitMe("hide");
}

