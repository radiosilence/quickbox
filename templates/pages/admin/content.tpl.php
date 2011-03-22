<?php // content.tpl.php
////////////////////////
// Template for content management interface ?>
<script type="text/javascript">
	<!--
	function deletecontent(id,name){
		var answer = confirm ("Are you sure you wish to delete the content \"" + name + "\"")
		if (answer){
			window.location="<?php echo $tData['pagePrefix']?>admin/content<?php echo janitor::getUrlString("&delete=")?>" + id
		}
	}
	function toggle(id) {
	  if(document.getElementById) {
	    var el = document.getElementById(id);
	    el.style.display = (el.style.display == 'none') ? 'block' : 'none';
	  }
	}
	// -->
</script>
<?php if(!$tData['tinymce_disable']): ?>
<!-- TinyMCE -->
<script type="text/javascript"
	src="<?php echo $tData['htmlRoot']?>qbres/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	// O2k7 skin
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "cbx",
		theme : "advanced",
		skin : "o2k7",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		theme_advanced_resizing : true,


		// Example content CSS (should be your site CSS)
		content_css : "<?php echo $tData['htmlRoot']?>css/blueprint/screen.css"

	});
</script>
<!-- /TinyMCE -->
<?php endif; ?>
<div id="admin_content">
  <?php include $this->tplPathComponent('adminNav')?>

  <h2>Content Management</h2>
  <?php $form = $this->tData['form']['data']; ?>
  <div id="pagesForm">
<div class="adminNav">
<h4><a href="<?php echo $tData['pagePrefix']?>admin/content"> Create
New... </a></h4>
<p>Or to edit, select existing content:</p>
        <?php	foreach($this->tData['form']['pageList'] as $k => $v): ?>
        <h4><a href="javascript:toggle('<?php echo $k?>');">
            <?php echo $k?>
          </a></h4>
<ul id="<?php echo $k?>" style="display:<?php echo ($v['selected'] ? 'block' : 'none')?>;">
          <?php foreach($v['pages'] as $j => $l): ?>
          <li><span class="pagename"><?php echo substr($j,0,34)?></span><br />
            <?php foreach($l as $z => $x): ?>
              <span
		<?php echo ($x['selected'] ? ' style="font-weight:bold;"' : '')?>> <a
		href="<?php echo $tData['pagePrefix']?>admin/content<?php echo janitor::getUrlString("&id=$z")?>"><?php echo $x['name']?></a>
	<a
		href="javascript:deletecontent('<?php echo $z?>','<?php echo $x['name']?>')">
	[x]</a> </span><br />
            <?php	endforeach; ?>
					</li>
          <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
    </div>
<div class="adminContent">
<h3>
        <?php echo ($this->tData['form']['editing'] ? 'Edit' : 'Add')?> Content
      </h3>
      <?php if($form == $this->tData['form']['submitting'] && isset($this->tData['form']['validation']['existant']['invalid'])): ?>
        <?php foreach($this->tData['form']['validation']['existant']['invalid'] as $k): ?>
      <p class="warningtext"><?php echo $k?> is invalid.</p>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php if($form == $this->tData['form']['submitting'] && isset($this->tData['form']['validation']['untaken']['invalid'])): ?>
        <?php foreach($this->tData['form']['validation']['untaken']['invalid'] as $k => $v): ?>
      <p class="warningtext"><?php echo $k?> &#8216;<?php echo $v?>&#8217; already exists.</p>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php if($this->tData['form']['validation']['passed']): ?>
      <p class="infotext">Database Updated.</p>
      <?php endif; ?>
      <form
	action="<?php echo $tData['pagePrefix']?>admin/content<?php echo janitor::getUrlString(($_GET['id'] ? "id=".$_GET['id'] : null)."&commit=true")?>"
	method="POST">
<p><label for="qbPageId"> Page: </label> <select name="qbPageId">
	<option value="">Select a Page...</option>
            <?php foreach($this->tData['form']['pages'] as $a => $b): ?>
              <option
		value="<?php echo (strpos($a,'sect') !== false ? '' : $a)?>"
		<?php echo ($a == $form['qbPageId'] ? 'selected' : '')?>><?php echo $b?></option> 
            <?php endforeach; ?>
          </select></p>
<p><label for="name"> Name: </label> <input type="text" name="name"
	value="<?php echo $form['name']?>" /></p>
<p><label for="title"> Identifiable Title: </label> <input type="text"
	name="title" value="<?php echo $form['title']?>" /></p>
<p>Hints: To get a page URL relative to the site root, type
{page}page_name - Instead of worrying about relative/absolute links etc.
</p>
<p><textarea name="content" class="contentBox" id="cbx"><?php echo $form['content']?></textarea>
</p>
<p style="text-align: right;"><a
	href="<?php echo $PHP_SELF?>?tinymce_disable=<?php echo ($tData['tinymce_disable'] ? 'false' : 'true')?>">Toggle
WYSIWYG</a></p>
<p style="text-align: center;"><input type="hidden" name="id"
	value="<?php echo $form['id']?>" /> <input type="submit"
	value="<?php echo ($this->tData['form']['editing'] ? 'Save' : 'Create')?>"
	style="width: 100px" /></p>
</form>

<p style="text-align: right"><a href="&admin/content">&lt;&lt; Back to
Content Management</a></p>
</div>
</div>
</div>