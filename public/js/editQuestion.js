$(document).ready(function(){

    $(document).on('click','.editButton', function(){ 

        let content = $(this).data('content');
        let idq     = $(this).data('id');
        let idexam  = $(this).data('idexam');


        $('.submitBtn').prop('disabled', true);

        $('#questionContent').val(content);

        $('#questionContent').keyup(function(){

            if($(this).val() != '') $('.submitBtn').prop('disabled', false);
            else $('.submitBtn').prop('disabled', true);
        });

        $(document).on('click','.submitBtn', function(){

            if($('#questionContent').val()!=''){

                content = $('#questionContent').val();

                $.ajax({
                    url: 'editQuestion',
                    method: 'POST',
                    data:{ id       : idq,
                           idexam   : idexam,
                           content  : content
                        }
                    }).done(function(msg){
                        if(msg != 'ERROR'){
                            swal({
                                title: 'Question modified!',
                                icon: 'success',}).then(() => {window.location.reload();});
                        }
                        else{
                            swal('Something went wrong!', {
                            icon: "warning",});
                        }
                    });

            }

        });

    });
});