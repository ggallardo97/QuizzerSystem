$(document).ready(function(){

    $(document).on('click','.submitBtnAddExam', function(){ 

        if($('#titleExam').val()!=''){

            let title = $('#titleExam').val();

            $.ajax({
                url: 'addExam',
                method: 'POST',
                data:{ 
                        title : title
                    }
                }).done(function(msg){
                    if(msg != 'ERROR'){
                        swal({
                            title:  'Exam added successfully!',
                            icon:   'success',}).then(() => {window.location.reload();});
                    }else{
                        swal('Something went wrong!', {
                        icon: "warning",});
                    }
                });
        }

    });

});