<?php
	if (!defined("ACCESS")) die("Error: You don't have permission to access here...");

	$twitter  = recoverPOST("twitter", encode($data[2]["Twitter"]));
	$facebook = recoverPOST("facebook", encode($data[2]["Facebook"]));
	$linkedin = recoverPOST("linkedin", encode($data[2]["Linkedin"]));
	$google   = recoverPOST("google", encode($data[2]["Google"]));
	$viadeo   = recoverPOST("viadeo", encode($data[2]["Viadeo"]));


	echo div("edit-profile", "class");
		echo formOpen($href, "form-add", "form-add");

			echo div("row", "class");

				echo div("span4", "class");
					echo formInput(array(
						"name"      => "twitter", 
						"class"     => "field-title span3 field-full-size",
						"field"     => "Twitter", 
						"p"         => true,
						"maxlength" => "150",
						"pattern" 	=> "^[\w\.]{3,150}$",
						"placeholder" => __("Username"),
						"value"     => $twitter
					));
				echo div(false);

				echo div("span4", "class");
					echo formInput(array(
						"name"      => "facebook", 
						"class"     => "field-title span3 field-full-size",
						"field"     => "Facebook", 
						"p"         => true,
						"maxlength" => "150",
						"pattern" 	=> "^[\w\.]{3,150}$",
						"placeholder" => __("ID or Username"),
						"value"     => $facebook
					));
				echo div(false);

			echo div(false);

			echo div("row", "class");

				echo div("span4", "class");
					echo formInput(array(
						"name"      => "linkedin", 
						"class"     => "field-title span3 field-full-size",
						"field"     => "LinkedIn", 
						"p"         => true,
						"maxlength" => "150",
						"pattern" 	=> "^[\w\.]{3,150}$",
						"placeholder" => __("Username"),
						"value"     => $linkedin
					));
				echo div(false);

				echo div("span4", "class");
					echo formInput(array(
						"name"      => "google", 
						"class"     => "field-title span3 field-full-size",
						"field"     => "Google+", 
						"p"         => true,
						"maxlength" => "150",
						"pattern" 	=> "^[\w\.]{3,150}$",
						"placeholder" => __("ID or Username"),
						"value"     => $google
					));
				echo div(false);

			echo div(false);

			echo div("row", "class");

				echo div("span4", "class");
					echo formInput(array(
						"name"      => "viadeo", 
						"class"     => "field-title span3 field-full-size",
						"field"     => "Viadeo", 
						"p"         => true,
						"maxlength" => "150",
						"pattern" 	=> "^[\w\.]{3,150}$",
						"placeholder" => __("Username"),
						"value"     => $viadeo
					));
				echo div(false);

			echo div(false);
			
			echo formInput(array(
				"name"  => "save", 
				"class" => "btn btn-success", 
				"value" => __("Save"), 
				"type"  => "submit"
			));

		echo formClose();
	echo div(false);
?>
<script>
	var acceptLabel = "<?php echo __("Accept"); ?>",
		cancelLabel = "<?php echo __("Cancel"); ?>",
		inputLabel  = "<?php echo __("Input your password"); ?>",
		btnSelector = 'input[type="submit"]:first';
</script>