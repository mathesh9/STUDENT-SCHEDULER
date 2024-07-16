<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT c.*, concat(u.firstname, coalesce(concat(' ',u.middlename), ''), ' ', u.lastname) as uname from `category_list` c inner join `users` u on c.user_id = u.id where c.id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer {
        display: none;
    }
</style>
<div class="container-fluid">
    <dl>
        <?php if ($_settings->userdata('type') == 1) : ?>
            <dt class="text-muted">User</dt>
            <dd class="pl-4"><?= isset($uname) ? $uname : "" ?></dd>
        <?php endif; ?>
        <dt class="text-muted">Name</dt>
        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>
        <dt class="text-muted">Status</dt>
        <dd class="pl-4">
            <?php if ($status == 1) : ?>
                <span class="badge badge-navy bg-gradient-navy px-3 rounded-pill">Active</span>
            <?php else : ?>
                <span class="badge badge-light bg-gradient-light border text-dark px-3 rounded-pill">Inactive</span>
            <?php endif; ?>
        </dd>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
    <hr>
    <h4>Tasks in this Category</h4>
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Task Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $tasks = $conn->query("SELECT * FROM `schedule_list` WHERE category_id = '{$id}'");
            while ($row = $tasks->fetch_assoc()) :
            ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td class="text-center">
                        <?php if ($row['status'] == 'Completed') : ?>
                            <span class="badge badge-success">Completed</span>
                        <?php else : ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('.delete_task').click(function() {
            _conf("Are you sure to delete this Task permanently?", "delete_task", [$(this).attr('data-id')])
        })
    })

    function delete_task($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_schedule",
            method: "POST",
            data: {
                id: $id
            },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>