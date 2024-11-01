<?php

if(!class_exists("PD_Options_Page")):
class PD_Options_Page {
  
  var $capability;
  var $menu_title;
  var $page_title;
  var $slug;
  var $sections;
  
  function __construct($page_title,$menu_title='',$slug,$capability="manage_options") {
    $this->page_title = $page_title;
    
    if($menu_title) {
      $this->menu_title = $menu_title;
    }
    else {
      $this->menu_title = $page_title;
    }
    
    $this->capability = $capability;
    
    $this->slug = $slug;

    add_action( 'admin_menu', array($this,'add_admin_menu') );
    add_action( 'admin_init', array($this,'admin_init')  );
 
    
  }
  
  function add_admin_menu() {
    add_options_page($this->page_title,$this->menu_title,$this->capability,$this->slug,array($this,'options_page'));
    
  }
  
  function admin_init() {

    
    if(!$this->sections OR !count($this->sections))return;
    foreach($this->sections as $section) {

      register_setting($this->slug,$section->setting_name);
      
      add_settings_section(
        $section->id, 
        $section->title, 
        array($section,'settings_section_callback'), 
        $this->slug
      );
      $section->page = $this->slug;
      

      foreach($section->fields as $field_name=>$field_data) {        
        add_settings_field($field_name,$field_data['label'],array($section,"write_field_".$field_name),$this->slug,$section->id);
      }

    }
    
    
  }
  
 
  function options_page() {
	?>
	<form action='options.php' method='post'>
		<?php
      settings_fields( $this->slug);
      do_settings_sections($this->slug);
      
		submit_button();
		?>

	</form>
	<?php    
  }
  
  
  
  
  
  
}
endif; // class exists

if(!class_exists("PD_Options_Page_Section")):
class PD_Options_Page_Section {
  
  var $id;
  var $title;
  var $content;
  var $fields;
  var $setting_name;
  var $section_intro;
  var $page;
  
 function settings_section_callback() {
    echo $this->section_intro;
  }
  

  function __call($name, $arguments) {
    
    if(substr($name,0,12)=='write_field_') {
      
      $field_name = substr($name,12);
      
      $field_data = $this->fields[$field_name];
      
      if(!isset($field_data['type']))$field_data['type']=false;
      
      switch($field_data['type']) {
        
        case "select":
          $this->write_field_select($field_name,$field_data);
        break;
        case "radio":
          $this->write_field_radio($field_name,$field_data);
        break;
        case "checkbox":
          $this->write_field_checkbox($field_name,$field_data);
        break;
        case "textarea":
          $this->write_field_textarea($field_name,$field_data);
        break;
      
        default:
          $this->write_field_input($field_name,$field_data);
      }
      
      // return when we've successfully written a field.
      return;
    }
    
    // if we get here then it means we called a bad function name.
    trigger_error("Undefined function '$name' called in PD_Options_Page_Section", E_USER_ERROR);
    
    
  }
  
  function write_field_select($name,$data) {
      $options = get_option($this->setting_name);
      
      echo "<select name='{$this->setting_name}[{$name}]'>";
      
      foreach($data['options'] as $key=>$val) {
        $sel = ($options[$name]==$key)?" selected='selected' ":"";
        echo "<option value='$key' $sel >$val</option>";
      }
      
      echo "</select>";
  }
  function write_field_radio($name,$data) {
    
      $options = get_option($this->setting_name);
      foreach($data['options'] as $key=>$val) {
        $sel = ($options[$name]==$key)?" checked='checked' ":"";
        echo "<label><input type='radio' name='{$this->setting_name}[{$name}]' value='$key' $sel >$val</label><br/>";
      }
  }  
  function write_field_checkbox($name,$data) {
      $options = get_option($this->setting_name);
      $sel = ($options[$name])?" checked='checked' ":"";
    ?>
    <input type="checkbox"  name='<?php echo "{$this->setting_name}[{$name}]";?>' value="1" <?php echo $sel; ?> />
    <?php
  }  
  function write_field_input($name,$data) {
      $options = get_option($this->setting_name);
    ?>
    <input type="<?php echo $data['type']; ?>"
           name='<?php echo "{$this->setting_name}[{$name}]";?>'
           
           <?php if(isset($data['max']))echo "max='{$data['max']}' "; ?>
           <?php if(isset($data['min']))echo "min='{$data['min']}' "; ?>
           
           value="<?php echo $options[$name]; ?>"/>
    <?php
  }
  function write_field_textarea($name,$data) {
      $options = get_option($this->setting_name);
    ?>
    <textarea 
           name='<?php echo "{$this->setting_name}[{$name}]";?>'
           ><?php echo $options[$name]; ?></textarea>
    <?php
  }
    
        
    
} // end class
endif; // class exists

  