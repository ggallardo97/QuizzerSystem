<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../public/plugins/Datatables/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../public/plugins/Datatables/datatables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../public/plugins/Datatables/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Online Quiz System</title>
  </head>
  <body>
    <header>
    <div class="containerHead">
        <h1 class="text-center mt-4">Online Quiz System</h1>
        <?php 
          if(isset($_SESSION['user'])){

            echo "<a href='".base_url()."/public/quiz/logout' class='btn btn-outline-primary btnLogout'>Logout</a>";
          
          }
        ?>
      </div>
      <hr>
    </header>
    <?php 
      if(isset($_SESSION['user']) && $_SESSION['user']['category']==='teacher'){

        echo "<div class='containerBody' style='margin-left:20px'>
                <nav class='containerButtons'>
                  <li> 
                    <button type='button' data-toggle='modal' data-target='#modalFormAddExam' class='btn btn-outline-primary'>Add exam</button>
                  </li>
                  <li> 
                    <a href='".base_url()."/public/quiz/showExams' class='btn btn-outline-primary'>Exams</a>
                  </li>
                  <li> 
                    <a href='".base_url()."/public/quiz/showScores' class='btn btn-outline-primary'>Scores</a>
                  </li>
                </nav>";

      }else echo "<div class='container'>";
    ?>