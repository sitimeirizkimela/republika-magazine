<?php 
include "application/views/header_admin.php"; ?>
<div  class="content-wrapper">
     <section class="content">
          <div class="row">
  <div class="box box-primary">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
<?php echo form_open('admin/charts/delete'); ?>

<table aria-describedby="example1_info" role="grid" id="example1" class="table table-bordered table-striped dataTable">
    <thead>
<tr role="row">
        
        <th>id</th>
        <th>Title</th>
        <th>Content</th>
        <th>date</th>
        <th>Action</th>
    </tr>
     </thead>
    <?php foreach ($magazines as $magazine) { ?>
        
<tr role="row" class="<?php $i=0; echo ($i%2 == 0) ? "even" : "odd" ;?>">
            <td><?php echo form_checkbox('id[]',$magazine['id']); ?></td>
            <td><?php echo $magazine['title']; ?></td>
            <td><?php echo $magazine['content']; ?></td>
            <td><?php echo $magazine['dates']; ?></td>
            <td>
                <select class="action-select" style="width: 130px" onchange="comboChoice(this.value,'<?php echo $magazine['id'];?>' , 'magazine');">
                <option>--</option>
                <option value="form_update">Edit</option>
                <option value="delete">Delete</option>
            </select>
        </tr>
 
<?php } ?>
</table>

<div class="row">
    <div class="col-sm-2">
        <div aria-live="polite" role="status" id="example1_info" class="dataTables_info"></div>
        </div>
        <div class="col-sm-4">
        <div id="example1_paginate" class="dataTables_paginate paging_simple_numbers">
            
            <ul class="pagination">
              <?php echo $pagination; ?>
            </ul>

        </div>
    </div>
                 <div class="col-sm-2">
        
<input type="submit" value="delete" onclick="return confirm('are you sure ?');" class="btn btn-danger">
</div>
</div>
<div class='pagination'>
<h3>


</h3>
</div>
</div>
<?php echo form_close();?>
 <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->      
</div>      
<script>
 function comboChoice(funct,id,page) {
        if(funct && funct != "--"){
            window.location='/admin/' + page + '/' + funct + '/' + id;
        }
  }
  </script>
<?php include "application/views/footer_admin.php"; ?>


