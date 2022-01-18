<div class='container'>
    <div class='row'>
        <div class='col-lg-12'>
            <div class='table-responsive'>
                <table id='example' class='table table-striped table-bordered' style='width:100%'>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Question</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($questions as $row){ ?>
                        <tr>
                            <td>
                                <?php echo $row['idquestion']; ?>
                            </td>
                            <td>
                                <?php echo $row['question']; ?>
                            </td>
                            <td>
                                <button type="button" data-idexam="<?php echo $row['idexam']; ?>" data-content="<?php echo $row['question']; ?>" data-id="<?php echo $row['idquestion']; ?>" class="btn btn-primary btn-lg fa fa-edit editButton" data-toggle="modal" data-target="#modalForm"></button>
                                <button type="button" data-idexam="<?php echo $row['idexam']; ?>" data-id="<?php echo $row['idquestion']; ?>" class="btn btn-danger btn-lg fa fa-trash deleteButton"></button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<a href="<?php echo base_url().'/public/quiz/teacherOptions'; ?>" class="btn btn-primary" style="margin-left: 105px;">Menu</a>

        <!-- Modal-->
        <div class="modal fade" id="modalForm" role="dialog">
            <div class="modal-dialog" width="600">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                    <h4>Edit question</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">X</span>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form role="form" action="" id="editForm" method="POST">
                            <div class="form-group">
                                <label>Question</label>
                                <input type="text" class="form-control" id="questionContent"/>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary submitBtn">EDIT</button>
                    </div>
                </div>
            </div>
        </div>