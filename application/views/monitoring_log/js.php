<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/datatable/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css"/>

<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/js/jquery.dataTables.min.js"></script> 
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/js/dataTables.rowsGroup.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/jszip/jszip.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/pdfmake/pdfmake.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/pdfmake/vfs_fonts.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.colVis.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.flash.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.html5.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {

    var datalist = $('#datalist').dataTable({ 
 
        "processing": true, 
        "serverSide": true, 
        "scrollX": true,
        "order": [], 
        rowsGroup: [
            'first:name',
            'second:name'
        ],
        "ajax": {
            "url": "<?=$base_url.$page?>/data/datalist",
            "data":{"<?=$csrf_token_name?>":"<?=$csrf_hash?>"},
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0,1 ,7], 
                "orderable": false, 
            },
        ],
        columns: [
            {
                name: 'seq',
            },
            {
                name: 'id',
            },
            {
                name: 'source',
            }, 
            {
                name: 'datetime',
            },
            {
                name: 'node',
            },
            {
                name: 'keypress',
            },
            {
                name: 'description',
            }, 
            {
                name: 'record',
            },
        ],
        rowsGroup: [
            'id:name',
            'record:name'
        ],
        //pageLength: '20'

    });

    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1)?>').addClass('active');
    $('#<?=$this->uri->segment(1)?>').parent().parent().parent('.has-treeview').addClass('menu-open');
    $('#type').on('change',function() {
        if(this.value=='voice'){
            $('#voicepath').prop('disabled',false);
        }else{
            $('#voicepath').prop('disabled',true);
        }
    });
    if($('#type').val()=='voice'){
        $('#voicepath').prop('disabled',false);
    }else{
        $('#voicepath').prop('disabled',true);
    }

} );

</script>