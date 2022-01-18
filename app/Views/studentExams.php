<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div class='table-responsive'>
                <table id='example' class='table table-striped table-bordered' style='width:100%'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($exams as $row){ ?>
                        <tr>
                            <td>
                                <?php echo $row['idexam']; ?>
                            </td>
                            <td>
                                <?php echo $row['title']; ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url().'/public/quiz/examStart?idexam='.$row['idexam']; ?>" class="btn btn-primary">Go</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<a href="<?php echo base_url().'/public/quiz/logout'; ?>" style="margin-left: 110px;" class="btn btn-outline-primary">Logout</a>