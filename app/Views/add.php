<div>
  <div class="alert alert-primary">
    Add a question
  </div>
  <?php if($right){ ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
      Question added successfully!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php }else{ if($wrong){ ?>

  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    Something went wrong! :(
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php }} ?>

  <form method="POST" action="<?php echo base_url().'/public/quiz/addQuestion?idexam='.$idexam; ?>">
    <div class="form-row">
      <div class="form-group col-md-8">
        <label for="question">Question</label>
        <input type="text" class="form-control" id="question_text" name="question_text" value="" required>
      </div>
      <div class="form-group col-md-6">
        <label for="choice1">Choice [1]</label>
        <input type="text" class="form-control"  name="choices[]" value="" required>
      </div>
      <div class="form-group col-md-6">
        <label for="is_correct">Correct answer number</label>
        <input type="number" min="1" max="5" class="form-control" id="iscorrect" name="iscorrect" value="" required>
      </div>
    </div>
    <input type="button" class="btn btn-primary" value="Add choice" id="addChoice">
    <input type="button" class="btn btn-danger" value="Remove choice" id="removeChoice"><br><br>
    
    <button type="submit" class="btn btn-primary">Add question</button>

  </form>
</div>
<?php 
    include('modalAddExam.php');
?>
