<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div class='table-responsive'>
                <table id='example' class='table table-striped table-bordered' style='width:100%'>
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Name</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($scores as $row){ ?>
                        <tr>
                            <td>
                                <?php echo $row['title']; ?>
                            </td>
                            <td>
                                <?php echo $row['nameus']; ?>
                            </td>
                            <td>
                                <?php echo $row['score']; ?>
                            </td>
                            <td>
                                <?php echo date('d-m-Y',strtotime($row['dateexam'])); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php 
    include('modalAddExam.php');
?>