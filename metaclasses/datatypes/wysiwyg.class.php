<?php
/**
 * wysiwyg.datatype.php
 *
 * @version $Id$
 * @copyright 2007
 */
class wysiwyg extends validate
{
	public static $fieldtype = 'TEXT';
	public $scripts = array (
		'tinymce' => '<script type="text/javascript" src="/qbres/js/tinymce/tiny_mce.js"></script>'
	);

	function dataTypeSpecific ()
	{
		return $this->invalid;
	}

	function formField ($name, $title, $value = null)
	{
		
		switch ($_SESSION['userVars']['wysiwyg'])
		{
			case 'tinymce':
				$return .= '<script type="text/javascript">
					tinyMCE.init({
						mode : "exact",
						elements : "' . $name . '",
						theme : "advanced",
						skin : "o2k7",
						plugins : "style,layer,table,save,advhr,advimage,advlink,iespell,media,contextmenu,paste,directionality,",
						theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,code,|,forecolor,backcolor",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true
					});
					</script>';
				break;
		}
		$return .= '<label for="' . $name . '">
					' . $title . '
				</label><br/>
				<textarea  id="' . $name . '" name="' . $name . '" rows="5" cols="20">' . $value . '</textarea>';
		
		return $return;
	}
}
?>
