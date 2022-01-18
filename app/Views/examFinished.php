<h2><?php echo $title;?></h2>
<p>Welcome <?php echo $_SESSION['user']['username']; ?>!</p>
<p>You have already finished this test!</p>

<ul>
  <li><strong>Type:</strong> Multiple choice</li>
  <li><strong>Your score: <?php echo $score; ?></strong></li>
</ul>

<a href="<?php echo base_url().'/public/quiz/logout'; ?>" class="btn btn-outline-primary">Logout</a>
<a href="<?php echo base_url().'/public/quiz/studentExams'; ?>" class="btn btn-outline-primary">Menu</a>