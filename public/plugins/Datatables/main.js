//Código para Datables

//$('#example').DataTable(); //Para inicializar datatables de la manera más simple

$(document).ready(function() {    
    $('#example').DataTable({
    //para cambiar el lenguaje a español
        "language": {
                "lengthMenu": "Show _MENU_ registers",
                "zeroRecords": "No results found",
                "info": "Showing registers from _START_ to _END_ of a total of _TOTAL_ registers",
                "infoEmpty": "Showing registers from 0 to 0 of a total of 0 registers",
                "infoFiltered": "(filtered of a total of _MAX_ registros)",
                "sSearch": "Search:",
                "oPaginate": {
                    "sFirst": "First",
                    "sLast":"Last",
                    "sNext":"Next",
                    "sPrevious": "Previous"
			     },
			     "sProcessing":"Processing...",
            }
    });     
});