$(function(){

	// summernote editor
	$('.summernote').summernote({
		height:300
	});

	//simplemde editor
	var simplemde = new SimpleMDE({ element: $("#simplemde")[0] });
		simplemde.value("This text will appear in the editor");
});