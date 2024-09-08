<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <select id='lottery'><option value="all">All Lotteries</option><op tion value="all">All</option><?php Lottery::getLotteriesOptions(); ?></select></span>
            <?php if($_GET['loti']=='all'){ 
                    Dashboard::makeProgressLottery(); 
                }else{
                    $lot = $_GET['loti'];
                    Dashboard::makeagentProgress($lot);
                } 
                
            ?>
        </div>


    </div>
</div>
<script>
    $(document).ready(function() {
        $('#lottery').change(function() {
            var selectedAgent = $(this).val();
            var baseUrl = window.location.href.split('?')[0];  // Get the base URL without query parameters
            window.location.replace(baseUrl + '?loti=' + encodeURIComponent(selectedAgent));
        });
    });
</script>