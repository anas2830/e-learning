
$(document).on('click', '.data-list #delete', function(e) {
    e.preventDefault();
    var deleteLinkUrl = $(this).attr('delete-link');
    var dataType = ($(this).attr('data-type')) ? $(this).attr('data-type') : 'html';
    var callBack = ($(this).attr('callback')) ? $(this).attr('callback') : false;
    var csrf = $(this).find("input[name='_token']").val();
    swal({
        title: "Are you sure?",
        text: "Once deleted, You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!",
        closeOnCancel: false
    }, 
    function(isConfirm){
        if (isConfirm) {
            
            $.ajax({
                url : deleteLinkUrl,
                type: "POST",
                data: {"_token": csrf, '_method':'DELETE'}, 
                dataType: dataType,
                success:function(data){
                    var dataError = (dataType=="html") ? data.trim() : data.error;
                    if((typeof dataError!==typeof undefined) && (dataError)) {
                        swal({
                            title: "Oops...",
                            text: dataError,
                            confirmButtonColor: "#EF5350",
                            type: "error"
                        });
                    } else {
                        swal({
                            title: "Deleted!",
                            text: "This data has been deleted!",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(isConfirm) {
                            if (isConfirm == true) {
                                // swal.close();
                                location.reload();
                            }
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Opps!!",
                        text: "Seems you couldn't submit form for a longtime. Please refresh your form & try again",
                        confirmButtonColor: "#EF5350",
                        type: "error"
                    });
                }
            });
        } else {
            swal({
                title: "Cancelled",
                text: "Your imaginary file is safe :)",
                confirmButtonColor: "#2196F3",
                type: "error"
            });
        }
    });
})


$(document).on('click', '.data-list .open-modal', function(e) {

    e.preventDefault();
    var modalTitle = $(this).attr('modal-title');
    var modalType = $(this).attr('modal-type');
    var modalSize = $(this).attr('modal-size');
    var className = $(this).attr('modal-class');
    var url = $(this).attr('modal-link');
    var selector =$(this).attr('selector');
    
    if (modalType=="create") {
        var successButton = "Save";
    } else if (modalType=="update") {
        var successButton = "Update";
    }
    $.ajax({
        url: url,
        type: 'GET',
        dataType: "html",
        success: function(response) {
            if (modalType!="show") {
                bootbox.dialog({
                    message: '<div id="' + selector + '">Loading . . .</div>',
                    size: modalSize,
                    title: modalTitle,
                    className: className,
                    buttons: {
                        close: {
                            label: "Close",
                            className: "btn-default"
                        },
                        success: {
                            label: successButton,
                            className: "btn-success disable-on-click",
                            "callback": function() {
                                $("#" + selector + " form").submit();
                                return false;
                            }
                        }
                    }
                }); 
            } else {
                bootbox.dialog({
                    message: '<div id="' + selector + '">Loading . . .</div>',
                    size: modalSize,
                    title: modalTitle,
                    className: className,
                    buttons: {
                        close: {
                            label: "Close",
                            className: "btn-default"
                        }
                    }
                }); 
            }
            $("#"+selector).html(response);
            $("#submit_btn").removeAttr("disabled", "disabled"); 
        }
    });
        
})

$('#custom_file_preview').on('click', '#custom_close', function() {
    $('#custom_file_preview').remove();
    $('#custom_file_input').show();
});

$('.upload-attachment a').on('click', function (event) {
    event.preventDefault();
    let openLink = $(this).attr('href');
    window.open(openLink, '_blank');
})