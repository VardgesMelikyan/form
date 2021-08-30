<div class="wrapper">
  <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST" id="vm-form-build">    
    <input type="text" name="form_title" class="form_title" required="required">
    <div id="form-container">
    <?php
      if(isset($_GET['id']) && !empty($_GET['id'])){
        include_once ('edit_form.php');
      }
    ?>  
    </div>
    <input type="submit" style="display: none" name="action" value="vm_simple_form">
    <input type="hidden" name="col-count" class="col-count" value="0">
  </form>
</div>
  <div class="grid-container">
    <div class="grid-item add_column" id="1col">1 Column</div>
    <div class="grid-item add_column" id="2col">2 Columns</div>
  </div>

<div class="field-popup pop">
  <div class="grid-container-full">
    <div class="select">
      <select id="select-custom-field">
        <option>Select Field Type</option>
        <option value="text">Text</option>
        <option value="email">Email</option>
        <option value="checkbox">Checkbox</option>
        <option value="radio">Radio</option>
        <option value="calendar">Calendar</option>
        <option value="upload">Upload</option>
      </select>
    </div>
  </div>
    <div><button value="Send Message" class="button" name="commit" id="field_submit"/>Submit</button><a style="float: right;" class="close" href="/">Cancel</a></div>
</div>
<?php 
