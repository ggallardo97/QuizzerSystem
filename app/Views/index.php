<h2><?php echo $title;?></h2>
<p>Welcome <?php echo $_SESSION['user']['username']; ?></p>
<ul>
  <li><strong>Total questions: <?php echo $totalq;?></strong></li>
  <li><strong>Type:</strong> Multiple choice</li>
  <li><strong>Estimated time: <?php echo $totalq*2; ?> minutes</strong> (2 minutes per question)</li>
</ul>
<a href="<?php echo base_url().'/public/quiz/question?idq=1&idexam='.$idexam; ?>" class="btn btn-outline-primary">Start</a>

