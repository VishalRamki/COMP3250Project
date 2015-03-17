<?php

	/*
	 *	Template Engine: 0.1a ALPHA
	 *	
	 *	NB: TEMPLATE ENGINE 0.0 ALPHA IS BASED UPON THESE FOLLOWING RESOURCES:
	 *		1. http://www.broculos.net/2008/03/how-to-make-simple-html-template-engine.html#.VQcj4uGnboc
	 *		2. http://www.gabrielemittica.com/cont/guide/how-you-can-create-a-light-and-useful-template-engine-for-php/10/1.html
	 *		3. http://stackoverflow.com/questions/5540828/how-to-make-a-php-template-engine
	 *
	 *
	 *	These codes are being used as a base to build the template engine upwards. 
	 *
	 *	11:55 PM - 16/03/15
	 *
	 *	Template Engine Phase One Completed;
	 *	The Template Engine can take array and 2d arrays and push the data onto a template file.
	 *	Additional Work required to prepare template files as well as other features of the 
	 *	template.
	 *	
	 *	10:32 AM [11:35AM] - 17/03/16
	 *
	 *	Slight Reordering of code makes it more managable as well as slight performance improvements.
	 *	In addition, I was able to find a noticeable bug in my code. I was able to quickly correct it. 
	 *	
	 *
	 */
	 
	// Global Template Vars 
	$PATH = "template/";
	// Template Libraries
	require_once($PATH. "template.tpl.engine.php");
	require_once($PATH. "multiview.tpl.engine.php");


?>



<?php
/*

BASE EXAMPLES;

$profile = new Template($PATH. "default/userprofile.php");
$multiProfile = new MultiView($PATH. "default/userprofiles.php");

$multiProfile->buildMultiStack(array(array("TE", "Xtra Xtra, Read All About it", "21", "UWI"), array("TR", "QWERTY", '33', "UWI")), array("username", "name", "age", "location"));

$profile->set("users", $multiProfile->mergeMultiStack());
$profile->buildHashFromArray(array("Team Cusine's Mega Awesome App (MAA)"), array("site-name"));

echo $profile->output();

*/
?>
