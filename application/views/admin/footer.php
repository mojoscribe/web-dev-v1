
    <!-- JavaScript -->
    <script type="text/javascript"> var baseUrl = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url('assets/admin/js/jquery-1.10.2.js');?>"></script>
    <script src="<?php echo base_url('assets/admin/js/jquery-migrate-1.2.1.min.js');?>"></script>

    <script src="<?php echo base_url('assets/admin/js/bootstrap.js');?>"></script>
    <script src="<?php echo base_url('assets/js/angular.min.js'); ?>"></script>
        <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="<?php echo base_url(); ?>assets/js/jquery.dataTables.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ng-table/ng-table.min.js"></script>

    <!-- Custom JS -->
    <?php if(isset($scripts)) { ?>
    	<?php foreach($scripts as $script) { ?>
    		<script type="text/javascript" src="<?php echo base_url('assets/'.$script);?>"></script>
    	<?php } ?>
    <?php } ?>

    
<!--     <?php if(isset($tableId)) { ?>
        <script type="text/javascript">
            $(document).ready( function () {
                $('#<?php echo $tableId; ?>').DataTable();
            } );
        </script>
    <?php }?> -->
    

  </body>
</html>