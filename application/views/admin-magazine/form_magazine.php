<?php 
include "application/views/header_admin.php"; ?>
<style>
textarea.form-control {
    height: 203px;
}
</style>
<div  class="content-wrapper">
	 <section class="content">
	      <div class="row">
<?php
if ($type_form == 'post') {
    echo form_open_multipart('admin/magazine/add');
} else {
    echo form_open_multipart('admin/magazine/update');
}
?>
 <?php echo form_error('images'); ?>
 <?php echo form_error('background'); ?>
 <?php echo form_error('status'); ?>
 <!--<link href="<?php echo base_url(); ?>assets/css/themes/all-themes.css" rel="stylesheet" />-->

 <script src="<?php echo base_url();?>js/jquery.minicolors.js" type="text/javascript"></script>
 <link href="<?php echo base_url() ;?>css/jquery.minicolors.css" rel="stylesheet" type="text/css">

    
       <input type="hidden" name="id" id="id" value="<?php if(isset ($isi['id'])) echo $isi['id'];  ?>" />
     
     <div class="col-md-12">
              <div class="box box-primary">
                   <div class="box-body form-horizontal">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No Edisi</label>
                      <div class="col-sm-10">
                      	<form action="<?=base_url('Magazine/add')?>" method="POST" enctype="multipart/form-data" >

                          <input type="text" class="form-control" name="title_ed" id="title_ed" value="<?php if(isset ($isi['title'])) echo $isi['title'];?>" />
                    </div>
                    </div>
                   <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Content</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="content_ed" id="content_ed" value="<?php if(isset ($isi['content'])) echo $isi['content'];?>" />
                    </div>
                    </div>
                                        <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Image</label>
                      <div class="col-sm-10">
                   		<?php echo form_upload('image_ed','""','class="form-control ch-image"');?></td>
					 	</div>
                    </div>
                    		
                   			
                     <div class="form-group">
                     
                     <table class="table table-bordered table-hover" id="container">
						<thead>
						<tr>
							<th class="text-center">Image</th>
						</tr>
						</thead>
						<tbody class="isianuan">

						<?php if($magazine) {
								$i=1;
								foreach($magazine as $chart) {?> 
									<tr id='addr0'>
										<td >

											<table class="table table-bordered table-hover">
												<tr>
													<td colspan=3 class="garisan">
														Halaman <?php echo $i; ?>
													</td>
												</tr>

												<tr>	
													<td>
														<input type="hidden" name="old_image[<?php echo $i; ?>]" id="old_image" value="<?php echo $chart['image']; ?>" />
      													<?php echo form_upload('image['.$i.']','""','class="form-control ch-image" multiple');?>
      												</td>
													<td><input required type="text" value="<?php echo $chart['title']; ?>"  class="form-control"  name='title[<?php echo $i; ?>]' placeholder='Title' /></td>
													<td><input required type="text" value="<?php echo $chart['author']; ?>" class="form-control" id="author[<?php echo $i; ?>]" name="author[<?php echo $i; ?>]" /></td>
													<td><button type="button" class="btn btn-danger ch-button"><i class="fa fa-remove"></i></button></td>
												</tr>

												<tr >	
													<td colspan=3><textarea required type="text" value=""  class="form-control"  name='content[<?php echo $i; ?>]' placeholder='content' /><?php echo $chart['content']; ?></textarea></td>
												</tr>
											</table>	
											<table class="table table-bordered table-hover">
												<tr>
													<td colspan=3 class="garisan">
														<tr>
															<td>Select Video :</td>
                											<td><input type="file" id="video" name="video" ></td>

														</tr>
													</td>
												</tr>
											</table>
										</td>	
									</tr>
							<?php 	$i++; 
									} 
								} else { ?>
								<tr id='addr0'>
										<td >
											<table class="table table-bordered table-hover">
												<tr>
													<td colspan=3 class="garisan">
														Halaman 1
													</td>
												</tr>
												<tr>	
													<td><?php echo form_upload('image[0]','""','class="form-control ch-image" multiple');?></td>
													<td><input required type="text" class="form-control"  name='title[0]' placeholder='Title' /></td>
													<td><input required type="text" class="form-control" id="author[0]" name="author[0]" /></td>
												</tr>
												<tr >	
														<td colspan=3><textarea required type="text" class="form-control"  name='content[0]' placeholder='content' /></textarea></td>
												</tr>
											</table>
											
												
												<table class="table table-bordered table-hover">
												<tr>
													<td colspan=3 class="garisan">
														<tr>
												
															<td>Select Video :</td>
                											<td><input type="file" id="video" name="video" ></td>

														</tr>
													</td>
												</tr>
											</table>
										</td>		
								</tr>
	
							<?php 	} ?>

						<tr id='addr_template' style="display:none">
							<td>
								<table class="table table-bordered table-hover">
									<tr>
											<td colspan=3 class="garisan">
													Halaman 
											</td>
									</tr>
									<tr>	
										<td><?php echo form_upload('image[99]','""','class="form-control ch-image"');?></td>
										<td><input type="text" class="form-control ch-title"  id="title[99]" name='title[99]' placeholder='Title' /></td>
										<td><input type="text" class="form-control ch-author" id="author[99]" name="author[99]" /></td>
										<td><button type="button" class="btn btn-danger ch-button"><i class="fa fa-remove"></i></button></td>
									</tr>
									<!--<tr>
										<td>Select Video:</td>
										<td><input type="text" class="form-control ch-title" id="video" name="video" ></td>
									</tr>-->
									<tr>
										<td colspan=3><textarea type="text" class="form-control ch-content"  id="content[99]" name='content[99]' placeholder='content' /></textarea></td>
									</tr>
								</table>	
								<table class="table table-bordered table-hover">
												<tr>
													<td colspan=3 class="garisan">
														<tr>
															<td>Select Video :</td>
                											<td><input type="file" id="video" name="video" ></td>

														</tr>
													</td>
												</tr>
											</table>
							</td>
						
						</tr>
						</tbody>

					</table>

                    
                                            <div class="box-footer">
              <button type="button" id="add_row" class="btn btn-warning ch-button" value="">Tambah Halaman</button>&nbsp;&nbsp;
              <?php $pst =  ($type_form == 'post') ? "post" : "update"; ?>
              <?php echo form_submit($pst, 'Submit','class="btn btn-success"'); ?>&nbsp;&nbsp;
                   </div>
                    </div>
              </div><!-- /.box-body -->
 
   

</form>
 <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->		
</div>		
<script>
$(document).ready(function () {
var counter = <?php echo ($magazine) ? count($magazine) : 1; ?>;
	$("#add_row").click(function () {
		var new_elem = $("#addr_template").clone()
				.appendTo("#container .isianuan")
				.show().attr("id", "addr" + counter);
				new_elem.find(".garisan").html("Halaman " +  parseInt(parseInt(counter) + 1)  );
				new_elem.find(".ch-title").attr("name","title[" + counter +"]");
				new_elem.find(".ch-content").attr("name","content[" + counter +"]");
				new_elem.find(".ch-author").attr("name","author[" + counter +"]");
				new_elem.find(".ch-image").attr("name","image[" + counter +"]");
				
		counter += 1;
	});
	$(".btn-danger").click(function () {
	});
	$(document).on('click', '.btn-danger', function() {
    	$(this).parent().parent().parent().parent().parent().parent().remove();
	});
});
</script>
<?php include "application/views/footer_admin.php"; ?>
