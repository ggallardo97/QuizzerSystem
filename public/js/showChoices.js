$(document).ready(function(){

    $(document).on('click','.showChoicesButton', function(){ 

        let idquestion = $(this).data('id');

        $.ajax({
            url: 'showChoices',
            method: 'POST',
            data:{ 
                    idquestion : idquestion
                }
            }).done(function(msg){
                $('#res').html(msg);
            });

        $(document).on('click','.editChoicesButton', function(){ 

            $("#editChoicesForm").on('submit', function(e){ //IDK why, but in others functions I don't need do this

                e.preventDefault();
           
             });

                let idchoice  = $(this).data('idchoice');
        
                if($('#choiceContent'+idchoice).val()!=''){
        
                    let content = $('#choiceContent'+idchoice).val();
                
                    $.ajax({
                            url: 'editChoice',
                            method: 'POST',
                            data:{ 
                                    idchoice    : idchoice,
                                    content     : content
                                }
                        }).done(function(msg){
                            if(msg != 'ERROR'){
                                swal({
                                    title: 'Choice modified!',
                                    icon: 'success',}).then(() => {window.location.reload();});
                            }else{
                                swal('Something went wrong!', {
                                icon: "warning",});
                                }
                            });
        
                        }
                        
            });
    });

});