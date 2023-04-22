/**
 * After the document successfully loaded, it hode all the mark as done button
 * of all the tasks which are alreay marked as done.
 */
$(document).ready(function() {
  $(".done>.mark").hide();
});

/**
 * After the document successfully loaded,if users try to add any task then this
 * function accepts the user input and sends that to the appropiate route and
 * load the list with newly added task.
 */
$(document).ready(function() {
  $("#add").click(function() {
    if ($("#task").val()) {
      $.ajax({
        url: "/addtask",
        method: "POST",
        data:
        {
          task: $("#task").val(),
        },
        datatype: "JSON",
        success: function (data)
        {
          var success = jQuery.parseJSON(data)["success"];
          if (success) {
            task();
          }
        },
      });
    }
    else {
      $("#error").html("please enter the task");
    }
  });
});

/**
 * After the document successfully loaded,if users try to mark any task as done
 * then it send that perticular id of the task to the specific route,
 * which isresponsible to handle this request and load the list with the updated
 * data.
 */
$(document).ready(function() {
  $(".mark").click(function() {
    $.ajax({
      url: "/markasdone",
      method: "POST",
      data:
      {
        taskId: $(this).attr("id"),
      },
      datatype: "JSON",
      success: function (data)
      {
        var success = jQuery.parseJSON(data)["success"];
        if (success) {
          task();
        }
      },
    });
  });
});

/**
 * After the document successfully loaded,if users try to delete any task then
 * it sends the task id to the responsible route and load the updated list.
 */
$(document).ready(function(){
    $(".delete").click(function () {
    $.ajax({
      url: "/delete",
      method: "POST",
      data: {
        taskId: $(this).attr("id"),
      },
      datatype: "JSON",
      success: function (data) {
        var success = jQuery.parseJSON(data)["success"];
        if (success) {
          task();
        }
      },
    });
  });
});

/**
 * This function is to load tasks.
 */
function task()
{
  $.ajax({
      url: "/task",
      success: function (data)
      {
        $(".container").html(data);
      }
    });
}
