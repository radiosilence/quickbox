<?php // pages.tpl.php
////////////////////////
// Template for page management interface ?>
<script type="text/javascript">
	<!--
	function deletepage(id,name){
		var answer = confirm ("Are you sure you wish to delete the page \"" + name + "\"")
		if (answer){
			window.location="<?php echo $this->tData['pagePrefix']?>admin/pages<?php echo janitor::getUrlString("&delete=")?>" + id
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
<div id="admin_content">
  <?php include $this->tplPathComponent('adminNav')?>

  <h2>Page Management</h2>
<div id="pagesForm">
<div class="adminNav2">
<h4><a href="<?php echo $this->tData['pagePrefix']?>admin/pages"> Create
New... </a></h4>
<p>Or to edit, select an existing page:</p>
      <?php foreach($this->tData['form']['pageList'] as $k => $v): ?>
        <h4><a href="javascript:toggle('<?php echo $k?>');">
            <?php echo $k?>
          </a></h4>
<ul id="<?php echo $k?>" style="display:<?php echo ($v['selected'] ? 'block' : 'none')?>;">
        <?php	foreach($v['pages'] as $j => $l): ?>
        <li
		<?php echo ($l['selected'] ? ' style="font-weight:bold;"' : '')?>><a
		href="<?php echo $this->tData['pagePrefix']?>admin/pages<?php echo janitor::getUrlString("&name=$j")?>"><?php echo $l['name']?></a>
	<a
		href="javascript:deletepage('<?php echo $j?>','<?php echo $l['name']?>')">
	[x]</a> <br />
	<span class="pagename"><?php echo substr($j,0,60)?></span></li>
        <?php endforeach; ?>
      </ul>
      <?php endforeach; ?>
    </div>
<div class="adminContent2">
<h3>
        <?php echo ($this->tData['form']['editing'] ? 'Edit' : 'Add')?> Page
      </h3>
      <?php $form = $this->tData['form']['data']; ?>
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
	action="<?php echo $this->tData['pagePrefix']?>admin/pages<?php echo janitor::getUrlString("&name=".$_GET['name']."&commit=true")?>"
	method="POST">
<p><label for="name"> Internal Name: </label> <input type="text"
	name="name" value="<?php echo $form['name']?>" /></p>
<p><label for="title"> Title: </label> <input type="text" name="title"
	value="<?php echo $form['title']?>" /></p>
<p><label for="pageTitle"> Page Title: </label> <input type="text"
	name="pageTitle" value="<?php echo $form['pageTitle']?>" /></p>
<p><label for="qbDispatcher"> Dispatcher: </label> <input type="text"
	name="qbDispatcher" value="<?php echo $form['qbDispatcher']?>" /></p>
<p><label for="path"> Template: </label> <input type="text" name="path"
	value="<?php echo $form['path']?>" /></p>
<p><label for="order"> Order: </label> <input type="text" name="order"
	style="width: 30px;" value="<?php echo $form['order']?>" /></p>
<p><label for="visible"> Visible on Menu: </label> <input
	type="checkbox" name="visible" style="width: 14px;"
	<?php if($form['visible']=='1') echo 'checked';?> /></p>
<p><label for="qbSectionId"> Section: </label> <select
	name="qbSectionId">
            <?php foreach($this->tData['form']['sections'] as $a => $b): ?>
              <option value="<?php echo $a?>"
		<?php echo ($a == $form['qbSectionId'] ? 'selected' : '')?>><?php echo $b?></option> 
            <?php endforeach; ?>
          </select></p>
<p><label for="qbSubPageTitle"> SubPage Group: </label> <select
	name="qbSubPageTitle">
	<option value="">Select a Subpage Group...</option>
            <?php foreach($this->tData['form']['subPages'] as $a => $b): ?>
              <option value="<?php echo $a?>"
		<?php echo ($a == $form['qbSubPageTitle'] ? 'selected' : '')?>><?php echo $b?></option> 
            <?php endforeach; ?>
          </select></p>
<p><label for="pageVars"> Page Variables: </label> <input type="text"
	name="pageVars" value="<?php echo $form['pageVars']?>" /></p>
<p>


<p style="text-align: center;"><input type="submit"
	value="<?php echo ($this->tData['form']['editing'] ? 'Save' : 'Create')?>"
	style="width: 100px" /></p>
</form>
<p style="text-align: right"><a
	href="<?php echo $this->tData['pagePrefix']?>admin/pages">&lt;&lt; Back
to Page Management</a></p>
</div>
</div>
</div>