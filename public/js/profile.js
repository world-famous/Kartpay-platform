$('#image-cropper').cropit();

$(document).on('click', '.select-image-btn', function () {
  
    $('.cropit-image-input').click();
});

$(document).on('change', '.cropit-image-input', function () {
    $('.cropit-image-preview').css('cursor', 'move');
    $('.avatar_hidden_tools').show();
});

$(document).on('click', '#update_avatar', function (e) {
    var el = $(this);
    var avatar = $('#image-cropper').cropit('export', {
															type: 'image/jpeg',
															quality: 0.33,
															originalSize: true,
														});
    var data = {'_method':'POST', '_token':'{{ csrf_token() }}', 'avatar':avatar};

    $.ajax({
        url: '{{ url("/profile/update_avatar") }}',
        type: 'POST',
        data: data,
        dataType: 'HTML',
        beforeSend: function () {
            el.prop('disabled', true);
        },
        complete: function () {
            el.prop('disabled', false);
        },
        success: function (res) {
            if (res == 'AVATAR_UPDATED') {
            } else {
            }
        },
        error: function (a,b,c) {
            return false;
        }
    });
});

function previewImage(input, imageId, elementInputId, elementHelpBlockId) {

    //Validation Image
    var file = input.files[0];
    var fileType = file["type"];    
    
    var ValidImageTypes = ["image/jpg", "image/jpeg"];
    if ($.inArray(fileType, ValidImageTypes) < 0) {
        clearImageInput(elementInputId, imageId, elementHelpBlockId);
        $('#' + elementInputId).attr('style', '');
        $('#' + elementHelpBlockId).html('<strong><font color="red">Image must be ".jpg or .jpeg" only</font></strong>');
        $('#' + elementHelpBlockId).attr('style', '');  
        return false;
    }
    
    var fileSize = file["size"];
    if(fileSize > 2000000) //Size > 2 MB
    {
        clearImageInput(elementInputId, imageId, elementHelpBlockId);
        $('#' + elementInputId).attr('style', '');
        $('#' + elementHelpBlockId).html('<strong><font color="red">Image must not greater than 2 MB</font></strong>');
        $('#' + elementHelpBlockId).attr('style', '');  
        return false;
    }
    //End Validation Image

    //Render Image
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + imageId).attr('style', 'max-width:200px; max-height:200px; cursor: pointer;');
            $('#' + imageId).attr('src', e.target.result);
            $('#' + elementHelpBlockId).html('');
            $('#' + elementHelpBlockId).attr('style', 'display:none;'); 
        }

        reader.readAsDataURL(input.files[0]);
    }
    //End Render Image
}

function showImage(elementId, title)
{
    $('.modal-title').html('Show Image ' + title);
    $('.modal-body').html('<img src="' + $('#' + elementId).attr('src') + '" style="max-width:800px; max-height:800px;"></img>');
    $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
    $('#myModal').modal('show');
}

function enableImageInput(elementInputId, elementPreviewId, elementHelpBlockId)
{
    $('#' + elementInputId).attr('style', '');
    $('#' + elementPreviewId).attr('style', 'max-width:200px; max-height:200px; cursor: pointer;');
    $('#' + elementHelpBlockId).html('');
    $('#' + elementHelpBlockId).attr('style', 'display:none;');
}

function clearImageInput(elementInputId, elementPreviewId, elementHelpBlockId)
{
    $('#' + elementInputId).val(null);
    $('#' + elementInputId).attr('style', 'display:none;');
    $('#' + elementPreviewId).attr('src', '');
    $('#' + elementPreviewId).attr('style', 'display:none;');
    $('#' + elementHelpBlockId).html('');
    $('#' + elementHelpBlockId).attr('style', 'display:none;');
}