<div class="d-flex justify-content-center">
    <form action="<?php echo base_url().'/public/quiz/loginUser';?>" method="POST">
        <input type="text" placeholder="User" name="username" required><br><br>
        <input type="password" placeholder="Password" name="userpassword" required><br><br>
        <input type="submit" class="btn btn-outline-primary" value="Login">
        <a href="<?php echo base_url().'/public/quiz/registerUser';?>" class="btn btn-outline-primary">Register</a>
    </form>
</div>
