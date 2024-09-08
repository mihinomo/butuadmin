<?php
$lot = Lottery::getLot($lid);
$book = Lottery::getBook($bid);



?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Series <?php echo $book['begin']." - ".$book['end']; ?></h1><br>
          </div><!-- /.col -->

                <div class="col-12">
                    <div class="card top-selling overflow-auto">

                        <div class="card-body pb-0">
                            <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Serial</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Payment</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php Book::sequence_table($lid,$book['begin'],$book['end'],$book['id']); ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>    