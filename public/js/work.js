$(document).ready(function(){
    $.ajax({
        type: 'GET',
        url: '/work',
        dataType: "json",
        success: function(data) {
            if(data.result == 1){
                $('#workasir').html('SHIFT 1').addClass('btn-inverse-success').removeClass('btn-inverse-danger');
            }
            else{
                $('#workasir').html('SHIFT 2').addClass('btn-inverse-danger').removeClass('btn-inverse-success');
            }
        }

    });
    
    $(document).on('click', '#workasir', function(){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/work/update',
            dataType: "json",
            success: function(data) {
                if(data.result == 1){
                    $('#workasir').html('SHIFT 1').addClass('btn-inverse-success').removeClass('btn-inverse-danger');
                }
                else{
                    $('#workasir').html('SHIFT 2').addClass('btn-inverse-danger').removeClass('btn-inverse-success');
                }
            }
        });
    });
});