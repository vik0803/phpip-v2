<script>
var relatedUrl = ""; // Identifies what to display in the Ajax-filled modal. Updated according to the href attribute used for triggering the modal
var resource = ""; // Identifies the REST resource for CRUD operations
var matter_id = $('#matter_id').text();
var csrf_token = $('input[name="_token"]').val();

$(document).ready(function() {

    // Show the title creation form when the title panel is empty
    if (!$("#titlePanel").text().trim())
        $("#addTitleForm").collapse("show");

    // Initialize popovers with custom template
    $('body').popover({
      selector: '[rel="popover"]',
      template: '<div class="popover border-info" role="tooltip"><div class="arrow"></div><h3 class="popover-header bg-info text-white"></h3><div class="popover-body"></div></div>'
    });

    // Close popovers by clicking outside
    $('body').on('click', function (e) {
      $('[rel="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
          $(this).popover('dispose');
        }
      });
    });

    // Actor creation popovers

    $('[rel="popover"]').on("shown.bs.popover", function(event) {
      $("#addActorForm").find('input[name="actor_id"]').autocomplete({
    		minLength: 2,
    		source: "/actor/autocomplete",
    		change: function (event, ui) {
    			if (!ui.item) $(this).val("");
    		}
      });
      $("#addActorForm").find('input[placeholder="Role"]').autocomplete({
    		minLength: 0,
    		source: "/role/autocomplete",
        select: function( event, ui ) {
          $("#addActorForm").find('input[name="shared"]').val(ui.item.shareable);
          if (ui.item.shareable) {
    			  $("#actorShared").prop('checked', true);
          } else {
            $("#actorNotShared").prop('checked', true);
          }
    		},
    		change: function (event, ui) {
          // Removes the entered value if it does not correspond to a suggestion
    			if (!ui.item) $(this).val("");
    		}
      }).focus(function () {
        // Triggers autocomplete search with 0 characters upon focus
        $(this).autocomplete("search", "");
      });
      $("body").on("change", '#addActorForm input[name="matter_id"]', function() {
        $("#addActorForm").find('input[name="shared"]').val(function( index, value ) {
          if (value == 1) {
            return 0;
          } else {
            return 1;
          }
        });
      });
      $("body").on("click", "#addActorSubmit", function() {
      	var request = $("#addActorForm").find("input").filter(function(){return $(this).val().length > 0}).serialize(); // Filter out empty values
      	$.post('/actor-pivot', request)
      	.fail(function(errors) {
      		$.each(errors.responseJSON.errors, function (key, item) {
      			$("#addActorForm").find('input[name=' + key + ']').attr("placeholder", item).parent().addClass("bg-danger");
      		});
    	  })
        .done(function() {
          $('.popover').popover('dispose');
          $("#actorPanel").load("/matter/" + matter_id + " #actorPanel > div");
        });
      });
    });


	// Ajax fill the opened modal and set global parameters
    $("#listModal").on("show.bs.modal", function(event) {
    	relatedUrl = $(event.relatedTarget).attr("href");
    	resource = $(event.relatedTarget).data("resource");
    	$(this).find(".modal-title").text( $(event.relatedTarget).attr("title") );
      $(this).find(".modal-body").load(relatedUrl);
    });

	// Ajax refresh various panels when a modal is closed
    $("#listModal, #classifiersModal").on("hide.bs.modal", function(event) {
    	//$(this).removeData('bs.modal');
        $("#multiPanel").load("/matter/" + matter_id + " #multiPanel > div");
    });

	$("#notes").keyup(function() {
		$("#updateNotes").removeClass('hidden-action');
		$(this).addClass('changed');
	});

	$("#updateNotes").click(function() {
		if ( $("#notes").hasClass('changed') ) {
			$.post("/matter/" + matter_id,
				{ _token: csrf_token, _method: "PUT", notes: $("#notes").val() });
			$("#updateNotes").addClass('hidden-action');
			$(this).removeClass('changed');
		}
		return false;
	});
});

$("#titlePanel").on("keypress", ".titleItem", function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var method = "PUT";
		var title = $(this).text().trim();
		if (!title)
			method = "DELETE";
		$.post('/classifier/' + $(this).attr("id"),
			{ _token: csrf_token, _method: method, value: title }
		).done(function() {
			$('#titlePanel').load("/matter/" + matter_id + " #titlePanel > div");
		});
	} else
		$(this).addClass("bg-warning");
});

$("#titlePanel").on("shown.bs.collapse", "#addTitleForm", function() {
   	$(this).find('input[name="type"]').autocomplete({
  		minLength: 0,
  		source: "/classifier-type/autocomplete/1",
  		select: function( event, ui ) {
  			$("#addTitleForm").find('input[name="type_code"]').val( ui.item.code );
  		},
  		change: function (event, ui) {
  			if (!ui.item) $(this).val("");
  		}
  	}).focus(function () {
        $(this).autocomplete("search", "");
    });
});

$("#titlePanel").on("click", "#addTitleSubmit", function() {
	var request = $("#addTitleForm").find("input").filter(function(){return $(this).val().length > 0}).serialize(); // Filter out empty values
	$.post('/classifier', request)
	.done(function() {
		$('#titlePanel').load("/matter/" + matter_id + " #titlePanel > div");
	}).fail(function(errors) {
		$.each(errors.responseJSON.errors, function (key, item) {
			$("#addTitleForm").find('input[name=' + key + ']').attr("placeholder", item).parent().addClass("bg-danger");
		});
	});
});

// Generic in-place edition of fields in a listModal

$("#listModal").on("keypress", "input.noformat", function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
		$.post(resource + $(this).closest("tr").data("id"), data)
		.done(function () {
			$("#listModal").find(".modal-body").load(relatedUrl);
			$("#listModal").find(".alert").removeClass("alert-danger").html("");
		}).fail(function(errors) {
			$.each(errors.responseJSON.errors, function (key, item) {
				$("#listModal").find(".modal-footer .alert").html(item).addClass("alert-danger");
			});
		});
	} else
		$(this).parent("td").addClass("bg-warning");
});

$('#listModal').on("focus", 'input[name$="date"].noformat', function() {
	$(this).datepicker({
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
		onSelect: function(date, instance) {
			var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
			$.post(resource + $(this).closest("tr").data("id"), data)
			.done(function () {
				$("#listModal").find(".modal-body").load(relatedUrl);
				$("#listModal").find(".alert").removeClass("alert-danger").html("");
			});
		}
	});
});

$('#listModal').on("click", 'input[name="assigned_to"].noformat', function() {
	$(this).autocomplete({
		minLength: 2,
		source: "/user/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
			if ($(this).hasClass("noformat")) $(this).parent().addClass("alert alert-warning");
		},
		select: function(event, ui) {
			this.value = ui.item.value;
			var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
			$.post(resource + $(this).closest("tr").data("id"), data)
			.done(function () {
				$("#listModal").find(".modal-body").load(relatedUrl);
				$("#listModal").find(".alert").removeClass("alert-danger").html("");
			});
		}
	});
});

$('#listModal').on("click",'input[type="checkbox"]', function() {
	var flag = 0;
	if ( $(this).is(":checked") ) flag = 1;
	$.post(resource + $(this).closest("tr").data("id"), { _token: csrf_token, _method: "PUT", done: flag })
	.done(function () {
		$("#listModal").find(".modal-body").load(relatedUrl);
		$("#listModal").find(".alert").removeClass("alert-danger").html("");
	})
});

$('#listModal').on("click", 'input[name="alt_matter_id"].noformat', function() {
	$(this).autocomplete({
		minLength: 2,
		source: "/matter/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		},
		select: function(event, ui) {
			this.value = ui.item.value;
			var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
			$.post(resource + $(this).closest("tr").data("id"), data)
			.done(function () {
				$("#listModal").find(".modal-body").load(relatedUrl);
				$("#listModal").find(".alert").removeClass("alert-danger").html("");
			});
		}
	});
});

// Specific processing in the task list modal

$("#listModal").on("click", "#addTaskToEvent", function() {
	$(this).parents("tbody").append( $("#addTaskFormTemplate").html() );
 	$("#addTaskForm").find('input[name="trigger_id"]').val( $(this).data("event_id") );
 	$("#addTaskForm").find('input[name="name"]').focus().autocomplete({
		minLength: 2,
		source: "/event-name/autocomplete/1",
		select: function( event, ui ) {
			$("#addTaskForm").find('input[name="code"]').val( ui.item.code );
		},
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
  });
 	$("#addTaskForm").find('input[name="assigned_to"]').autocomplete({
		minLength: 2,
		source: "/user/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
  });
 	$("#addTaskForm").find('input[name$="date"]').datepicker({
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
  });
});

$("#listModal").on("click", "#addTaskSubmit", function() {
	var request = $("#addTaskForm").find("input").filter(function(){return $(this).val().length > 0}).serialize(); // Filter out empty values
	$.post('/task', request)
	.done(function() {
		$('#listModal').find(".modal-body").load("/matter/" + matter_id + "/tasks");
	}).fail(function(errors) {
		$.each(errors.responseJSON.errors, function (key, item) {
      $("#addTaskForm").find('input[name=' + key + ']').attr("placeholder", item).parent().addClass("bg-danger");
		});
	});
});

$("#listModal").on("click", "#deleteTask", function() {
	$.post('/task/' + $(this).closest("tr").data("id"),
		{ _token: csrf_token, _method: "DELETE" }
	).done(function() {
		$('#listModal').find(".modal-body").load(relatedUrl);
	});
});

$("#listModal").on("click","#deleteEvent", function() {
	if ( confirm("Deleting the event will also delete the linked tasks. Continue anyway?") ) {
		$.post('/event/' + $(this).data('event_id'),
			{ _token: csrf_token, _method: "DELETE" },
			function() {
				$('#listModal').find(".modal-body").load(relatedUrl);
			}
		);
	}
});

// Specific processing in the event list modal

$("#listModal").on("click", "#addEvent", function() {
	$("#listModal").find("tbody").append( $("#addEventFormTemplate").html() );
   	$("#addEventForm").find('input[name="name"]').focus().autocomplete({
		minLength: 2,
		source: "/event-name/autocomplete/0",
		select: function( event, ui ) {
			$("#addEventForm").find('input[name="code"]').val( ui.item.code );
		},
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
	});
   	$("#addEventForm").find('input[name="alt_matter_id"]').autocomplete({
		minLength: 2,
		source: "/matter/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
	});
   	$("#addEventForm").find('input[name$="date"]').datepicker({
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
	});
});

$("#listModal").on("click", "#addEventSubmit", function() {
	var request = $("#addEventForm").find("input").filter(function(){return $(this).val().length > 0}).serialize(); // Filter out empty values
	$.post('/event', request)
	.done(function() {
		$('#listModal').find(".modal-body").load("/matter/" + matter_id + "/events");
	}).fail(function(errors) {
		$.each(errors.responseJSON.errors, function (key, item) {
			$("#addEventForm").find('input[name=' + key + ']').attr("placeholder", item).parent().addClass("bg-danger");
		});
	});
});

// Classifiers modal processing

$('#classifiersModal').on("keypress", "input.noformat", function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
		$.post('/classifier/'+ $(this).closest("tr").data("classifier_id"), data)
		.done(function () {
			$("td.bg-warning").removeClass("bg-warning");
			$("#classifiersModal").find(".alert").removeClass("alert-danger").html("");
		}).fail(function(errors) {
			$.each(errors.responseJSON.errors, function (key, item) {
				$("#classifiersModal").find(".modal-footer .alert").html(item).addClass("bg-danger");
			});
		});
	} else
		$(this).parent("td").addClass("bg-warning");
});

$('#classifiersModal').on("click", 'input[name="lnk_matter_id"].noformat', function() {
	$(this).autocomplete({
		minLength: 2,
		source: "/matter/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		},
		select: function(event, ui) {
			this.value = ui.item.value;
			var data = $.param({ _token: csrf_token, _method: "PUT" }) + "&" + $(this).serialize();
			$.post('/classifier/'+ $(this).closest("tr").data("classifier_id"), data)
			.done(function () {
				$('#classifiersModal').load("/matter/" + matter_id + " #classifiersModal > div");
				$("#classifiersModal").find(".alert").removeClass("alert-danger").html("");
			});
		}
	});
});

$("#classifiersModal").on("shown.bs.collapse", "#addClassifierForm", function() {
 	$("#addClassifierForm").find('input[name="type"]').autocomplete({
		minLength: 0,
		source: "/classifier-type/autocomplete/0",
		select: function( event, ui ) {
			$("#addClassifierForm").find('input[name="type_code"]').val( ui.item.code );
		},
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
	}).focus(function () {
  // Forces search with no characters upon focus
    $(this).autocomplete("search", "");
  });
 	$("#addClassifierForm").find('input[name="lnk_matter_id"]').autocomplete({
		minLength: 2,
		source: "/matter/autocomplete",
		change: function (event, ui) {
			if (!ui.item) $(this).val("");
		}
  });
});

$("#classifiersModal").on("click", "#addClassifierSubmit", function() {
	var request = $("#addClassifierForm").find("input").filter(function(){return $(this).val().length > 0}).serialize(); // Filter out empty values
	$.post('/classifier', request)
	.done(function() {
		$('#classifiersModal').load("/matter/" + matter_id + " #classifiersModal > div");
	}).fail(function(errors) {
		$.each(errors.responseJSON.errors, function (key, item) {
			$("#addClassifierForm").find('input[name=' + key + ']').attr("placeholder", item).parent().addClass("bg-danger");
		});
	});
});

$("#classifiersModal").on("click", "#deleteClassifier", function() {
	$.post('/classifier/' + $(this).closest("tr").data("classifier_id"),
		{ _token: csrf_token, _method: "DELETE" }
	).done(function() {
		$('#classifiersModal').load("/matter/" + matter_id + " #classifiersModal > div");
	});
	return false;
});
</script>
