<?php # English language file.
# System Errors
text::set('system/textNotFound', '&lt; Text "%s" was not found. &gt;');
text::set('system/fatalError', 'Exception: %s');
text::set('system/dbNoConnect', 'Could not connect to database server.');
text::set('system/dbNoDb', 'Could not select database.');
text::set('system/dbError', 'Database Error.');
text::set('system/msgSeperator', ': ');
text::set('system/404title', '404: Not Found');
text::set('system/404text', 'Unfortunately it has not been possible to locate the requested page.');
#Form Bits
text::set('form/save','Save');
text::set('form/reset','Reset');
text::set('form/delete','Delete');
text::set('form/foreignTableNotExist','Foreign key table for %s does not exist. You may not be able to save this entry.');
#Login Messages
text::set('login/loggedInAs','Logged in as <span class="alt">%s</span> (<a href="%s">Log Out</a>)');
text::set('login/failedLogin', 'Sorry, login failed. Try again.');
# Auth Messages
text::set('auth/notAuth','Sorry, you are not authorized to view this page.');
text::set('auth/notAuthTitle','Not Authorized');
# SQL Generator Errors
text::set('system/sql/invalidQueryType', 'Invalid query type "%s" passed to generator.');
# Validation Errors
text::set('validation/defaultValidationError', 'Something did not validate.');
text::set('validation/checkingForeignKeyForNonExistantTable', 'Trying to check foreign key for non existant table.');
text::set('validation/success', 'Submission was successful.');
text::set('validation/error_exists', '%s is missing.');
text::set('validation/error_unique', '%s must be unique.');
text::set('validation/error_validDate', '%s must be a valid date in the format YYYY-MM-DD.');
text::set('validation/error_alphaNumeric', '%s must consist of only letters and numbers.');
text::set('validation/error_realItem', '%s is not a real item.');
text::set('validation/error_realEmail', '%s is not a valid e-mail address.');
text::set('validation/error_notBothSame', '%ss are not the same.');
text::set('validation/errorsProcessing', 'There were errors processing your request. Please review them below:');
# Metaclass Errors
text::set('metaclass/missingClassFile', 'Class file is missing for class "%s."');
text::set('metaclass/couldNotCreateObject', 'Could not create object "%s"');
# Scaffold Related
text::set('scaffold/returntoitems', 'Return to Item List');
text::set('scaffold/createnew', 'Create New Item');
?>
