
<br><br>
<div class="card">
    <div class="card-header">
    <h3 class="card-title">Your Financial Position</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
    <table class="table table-sm table-borderless">
        <thead>
        <tr>
            <th>Account</th>
            <th style="width: 110px">Credit</th>
            <th style="width: 110px">Debit</th>
            <th style="width: 110px">Balance</th>
        </tr>
        </thead>
        <tbody>
        <?php Ledger::balanceSheet(); ?>
        </tbody>
    </table>
    </div>
    <div class="card-footer clearfix">
        <a href="/ledger/?r2=all" class="btn btn-sm btn-info float-left">View Ledger Book</a>
    </div>
    <!-- /.card-body -->
</div>
<style>
.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th {
    border: none;
}
</style>