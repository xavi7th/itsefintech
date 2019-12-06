$(function(){

	var todoTitle =  $("#todo-title");
		todoDesc  =  $("#todo-desc");
		todoList  =  $('#todo-list');
		

	// add todo item
	function addTodoItem() {
		if (todoTitle.filter(function() { return $(this).val(); }).length > 0) {
			var todoContent = "<li class='todo-list-item todo-item-new'><label class='control control-checkbox'><span>" + todoTitle.val() + "</span><input type='checkbox' name='todo-item-done' class='todo-item-done'><span class='control-icon'></span></label><p>"+ todoDesc.val() +"</p><button class='todo-item-delete'><i class='far fa-times-circle'></i></button></li>";
			console.log(todoTitle.val() + todoDesc.val());	  
			todoList.prepend(todoContent);
			todoTitle.val("");
			todoDesc.val("");
			$('#todo-modal').modal('hide');

			setTimeout(function() {
		        $('.todo-list-item').removeClass('todo-item-new');
		    }, 3000);
		} else{
			alert('Please enter a title.');

		}
	}

	/// delete todo item
	function deleteTodoItem(e, item) {
		e.preventDefault();
		$(item).parent().fadeOut('slow', function() { 
			$(item).parent().remove();
		});
	}

    // completed todo item    
	function completeTodoItem() {  
		$(this).parent().parent().toggleClass('todo-item-completed')
	}	

	
	$(function() {
		$("#add-todo-item").on('click', function(e){
			e.preventDefault();
			addTodoItem()
		});

		todoList.on('click', '.todo-item-delete', function(e){
			var item = this; 
			deleteTodoItem(e, item)
		})
	  
		$(document).on('click', ".todo-item-done", completeTodoItem);

	});


});


