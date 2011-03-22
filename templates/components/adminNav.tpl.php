<?php # Navigation bar in admin section ?>
<p id="adminNav">
    <?php if($tData['pageName'] == 'admin'): ?>
    <strong>Admin</strong>
    <?php else: ?>
    <a href="<?php echo $tData['pagePrefix']?>admin">Admin</a>
    <?php endif; ?>
    &nbsp;&#124;&nbsp;
    <?php if($tData['pageName'] == 'admin/pages'): ?>
    <strong>Pages</strong>
    <?php else: ?>
    <a href="<?php echo $tData['pagePrefix']?>admin/pages">Pages</a>
    <?php endif; ?>
    &nbsp;&#124;&nbsp;
    <?php if($tData['pageName'] == 'admin/content'): ?>
    <strong>Content</strong>
    <?php else: ?>
    <a href="<?php echo $tData['pagePrefix']?>admin/content">Content</a>
    <?php endif; ?>
    &nbsp;&#124;&nbsp;
    <?php if($tData['pageName'] == 'admin/users'): ?>
    <strong>Users</strong>
    <?php else: ?>
    <a href="<?php echo $tData['pagePrefix']?>admin/users">Users</a>
    <?php endif; ?>
</p>