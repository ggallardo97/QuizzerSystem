<div class="alert alert-dark">
  Question <?php echo $idq;?> of <?php echo $totalq; ?>
</div>
<p class="font-weight-bolder"><?php echo $questions[0]['question']; ?></p>

<?php foreach($choices as $c){ ?>
  <form method="POST" action="<?php echo base_url().'/public/quiz/process?idexam='.$idexam?>">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="question_id" value="<?php echo $c['idchoice'];?>" id="choice<?php echo $c['idchoice'];?>">
          <label class="form-check-label" for="choice<?php echo $c['idchoice'];?>"> <?php echo $c['choice']; ?>
          </label>
        </div>
        <input type="hidden" name="next_question" value="<?php echo $idq;?>">
    <?php } ?>
    <input type="submit" value="Send" class="btn btn-primary mt-3">
  </form>
