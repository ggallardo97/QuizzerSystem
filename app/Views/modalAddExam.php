 <!-- Modal for adding a exam-->
 <div class="modal fade" id="modalFormAddExam" role="dialog">
            <div class="modal-dialog" width="600">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                    <h4>Add exam</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">X</span>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form role="form" action="" id="editForm" method="POST">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" id="titleExam" required/>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary submitBtnAddExam">Submit</button>
                    </div>
                </div>
            </div>
        </div>