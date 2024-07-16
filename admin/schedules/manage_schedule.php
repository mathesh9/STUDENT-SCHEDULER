<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `schedule_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="schedule-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="category_id" class="control-label">Category</label>
            <select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>
                <option value="" disabled <?= !isset($category_id) ? 'selected' : "" ?>></option>
                <?php 
                $where = "";
                if($_settings->userdata('type') != 1){
                    $where = " and user_id = '{$_settings->userdata('id')}' ";
                }
                $category_qry = $conn->query("SELECT *,(SELECT concat(firstname, coalesce(concat(' ', middlename), ''), ' ', lastname) FROM `users` where id = category_list.id ) as uname FROM `category_list` where `status` = 1 and delete_flag = 0 {$where} ".(isset($category_id) && $category_id > 0? " or id = '{$category_id}'" : "")." order by `name` asc");
                while($row = $category_qry->fetch_assoc()):
                ?>
                <?php if($_settings->userdata('type') == 1): ?>
                <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? "selected" : '' ?>><?= $row['uname']. ' - ' . $row['name'] ?></option>
                <?php else: ?>
                <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? "selected" : '' ?>><?= $row['name'] ?></option>
                <?php endif; ?>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title" class="control-label">Task Title</label>
            <input type="text" name="title" id="title" class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : ''; ?>" required/>
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <textarea name="description" id="description" class="form-control form-control-sm rounded-0" rows="3" required><?php echo isset($description) ? $description : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="schedule_from" class="control-label">Schedule From</label>
            <input type="datetime-local" name="schedule_from" id="schedule_from" class="form-control form-control-sm rounded-0" value="<?php echo isset($schedule_from) ? date('Y-m-d\TH:i', strtotime($schedule_from)) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="schedule_to" class="control-label">Schedule To</label>
            <input type="datetime-local" name="schedule_to" id="schedule_to" class="form-control form-control-sm rounded-0" value="<?php echo isset($schedule_to) ? date('Y-m-d\TH:i', strtotime($schedule_to)) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="reminder_time" class="control-label">Reminder Time</label>
            <input type="datetime-local" name="reminder_time" id="reminder_time" class="form-control form-control-sm rounded-0" value="<?php echo isset($reminder_time) ? date('Y-m-d\TH:i', strtotime($reminder_time)) : ''; ?>">
        </div>
        
    </form>
</div>
<script>
    function start_load() {
        // Show a loading indicator (this can be customized as per your needs)
        $('body').append('<div id="loading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
    }

    function end_load() {
        // Hide the loading indicator
        $('#loading').remove();
    }

    $(document).ready(function(){
        $('#schedule-form').submit(function(e){
            e.preventDefault();
            start_load();
            var formData = $(this).serialize();
            console.log('Form Data:', formData);  // Debug statement
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_schedule",
                data: formData,
                method: 'POST',
                dataType: 'json',
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('AJAX Error:', {
                        jqXHR: jqXHR,
                        textStatus: textStatus,
                        errorThrown: errorThrown
                    });
                    alert_toast("An error occurred: " + textStatus + " - " + errorThrown, 'error');
                    end_load();
                },
                success: function(resp){
                    console.log('Response:', resp);  // Debug statement
                    if (resp && resp.status == 'success'){
                        location.reload();
                    } else {
                        alert_toast("An error occurred: " + resp.error, 'error');
                        end_load();
                    }
                }
            });
        });
    });
</script>
