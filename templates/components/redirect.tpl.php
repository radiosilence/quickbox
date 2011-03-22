<?php # Include this in templates before any output
header("Location: " . $tData['pageVars']['redirect']);
?>
<script language="javascript">
window.location = "<?php
echo $tData['pageVars']['redirect']?>"
</script>