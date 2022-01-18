$(document).ready(function(){

    const max_fields = 5;
    let x = 1;
            
    $('#removeChoice').hide();

    $('#addChoice').on('click',() => { 

        if(x < max_fields){
            x++;
            $('<div class="form-group"><label>Choice ['+x+']</label><input type="text" class="form-control" name="choices[]" value="" required></div>').insertBefore('#addChoice');
        }

        if(x > 1) $('#removeChoice').show();
    });

    $('#removeChoice').on('click',() => {
        $('#addChoice').prev().remove();
        x--;
        if(x === 1) $('#removeChoice').hide();
    });
});