<div class="alert alert-primary">
  Add Exam
</div>
<?php if($right){ ?>
  <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
    Exam added successfully!
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

<form method="POST" action="<?php echo base_url().'/public/quiz/addExam'; ?>">
  <div class="form-row">
    <div class="form-group col-md-8">
      <label for="question">Title</label>
      <input type="text" class="form-control" id="title" name="title" value="" required>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>

  <a href="<?php echo base_url().'/public/quiz/teacherOptions'; ?>" class="btn btn-primary">Menu</a>
  

</form>