$(document).ready(function(){

    $(document).on('click','.deleteButton', function(){ 

        let deleteId = $(this).data('id');
        let deleteIdExam = $(this).data('idexam');

        swal({
            title: "Are you sure?",
            text: "This question will be deleted from the exam!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
                if(willDelete){
                    $.ajax({
                        url: 'deleteQuestion',
                        method: 'POST',
                        data:{
                            id: deleteId,
                            idexam: deleteIdExam
                        }
                    }).done(function(msg){
                        if(msg != 'ERROR'){
                            swal({
                                title: 'Question deleted!',
                                icon: 'success',}).then(() => {window.location.href = 'showQuestions'});
                        }else{
                            swal('Something went wrong!',{
                            icon: "warning",});
                        }
                    });
                }
            });
    });
});