<?php // subPages.tpl.php
////////////////////////
// Template for subPage management interface ?>
<script type="text/javascript">
	<!--
	function deletesubPage(id,name){
		var answer = confirm ("Are you sure you wish to delete the content \"" + name + "\"")
		if (answer){
			window.location="<?php echo $this->tData['pagePrefix']?>admin/subPages<?php echo janitor::getUrlString("&delete=")?>" + id
		}
	}
	// -->
</script>
<div id="admin_content">
  <?php include $this->tplPathComponent('adminNav')?>
  <h2>Subpages Management</h2>
<div id="pagesForm">
<div class="adminNav" style="width: 340px;">
<h4><a href="<?php echo $this->tData['pagePrefix']?>admin/subPages">
Create New... </a></h4>
    <?php $form = $this->tData['form']['data']; ?>
      <?php if(is_array($this->tData['form']['subPageList'])): ?>
       <p>Or select an existing subpage to edit:</p>
        <?php	foreach($this->tData['form']['subPageList'] as $k => $v): ?>
        <h4>
          <?php echo $k?>
        </h4>
<ul>
          <?php foreach($v as $j => $l): ?>
              <li><a
		href="<?php echo $this->tData['pagePrefix']?>admin/subPages<?php echo janitor::getUrlString("&id=$j")?>"><?php echo $l?></a>
	<a href="javascript:deletesubPage('<?php echo $j?>','<?php echo $l?>')">
	[x]</a></li>
          <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
      <?php endif;  ?>
    </div>
<div class="adminContent" style="width: 540px;">
<h3><?php echo ($this->tData['form']['editing'] ? 'Edit' : 'Add')?> Subpage</h3>
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
	action="<?php echo $this->tData['pagePrefix']?>admin/subPages<?php echo janitor::getUrlString("&id=".$_GET['id']."&commit=true")?>"
	method="POST">
<p><label for="title"> Group Title: </label> <input type="text"
	name="title" value="<?php echo $form['title']?>" /></p>
<p><label for="qbPageNameLinked"> Linked to Page: </label> <select
	name="qbPageNameLinked">
            <?php foreach($this->tData['form']['pages'] as $a => $b): ?>
              <option
		value="<?php echo (strpos($a,'sect') !== false ? '' : $a)?>"
		<?php echo ($a == $form['qbPageNameLinked'] ? 'selected' : '')?>><?php echo $b?></option> 
            <?php endforeach; ?>
          </select></p>
<p><label for="order"> Order: </label> <input type="text" name="order"
	style="width: 30px;" value="<?php echo $form['order']?>" /></p>
<p style="text-align: center;"><input type="hidden" name="id"
	value="<?php echo $form['id']?>" /> <input type="submit"
	value="<?php echo ($this->tData['form']['editing'] ? 'Edit' : 'Add')?>"
	style="width: 100px" /></p>
</form>
</div>
</div>
</div>