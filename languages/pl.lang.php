<?php # Polish language file.
# System Errors
text::set('system/textNotFound', 'Text %s nie zostal odnaleziony.');
text::set('system/fatalError', 'Wyjatek: %s');
text::set('system/dbNoConnect', 'Nie mogę połaczyć się do serwera baz danych.');
text::set('system/dbNoDb', 'Nie mogę wybrać bazy danych.');
text::set('system/dbError', 'Błąd bazy danych.');
text::set('system/msgSeperator', ': '); 
text::set('system/404title', '404: Nie znaleziono');
text::set('system/404text', 'Nie było możliwe odnalezienie danej strony.');
#Form Bits
text::set('form/save','Zapisz');
text::set('form/reset','Resetuj');
text::set('form/delete','Usuń');
text::set('form/foreignTableNotExist','Obcy klucz tabeli dla %s nie istnieje. Mżesz nie być w stanie zapisać tą właściwość.');
#Login Messages
text::set('login/loggedInAs','Zalogowany jako <span class="alt">%s</span> (<a href="%s">Wylogowany</a>)');
text::set('login/failedLogin', 'Przepraszamy, błędny login.');
# Auth Messages
text::set('auth/notAuth','Przepraszamy, nie posiadasz autoryzacji do oglądania tej strony.');
text::set('auth/notAuthTitle','Nie autoryzowany');
# SQL Generator Errors
text::set('system/sql/invalidQueryType', 'Niewłaściwy rodzaj pytania %s zdany do generatora.');
# Validation Errors
text::set('validation/defaultValidationError', 'Coś jest nie właściwe.'); 
text::set('validation/checkingForeignKeyForNonExistantTable', 'Próba sprawdzenia obcego klucza dla nie istniejącej tabeli.');
text::set('validation/success', 'Formularz został poprawnie wysłany.');
text::set('validation/error_exists', '%s jest zagubiony.');
text::set('validation/error_unique', '%s musi być unikatowy.');
text::set('validation/error_validDate', '%s data musi być w formacie YYYY-MM-DD.');
text::set('validation/error_alphaNumeric', '%s musi składać się tylko z liter lub liczb.');
text::set('validation/error_realItem', '%s nie jest właściwą pozycją.');
text::set('validation/error_realEmail', '%s nie jest właściwym adresem email.');
text::set('validation/error_notBothSame', '%s nie są tem same.');
text::set('validation/errorsProcessing', 'Wystąpiły błędy w Twoim żądaniu. Proszę przejrzyj je poniżej:');
# Metaclass Errors
text::set('metaclass/missingClassFile', 'Plik klasy zagubiony dla klasy %s."');
text::set('metaclass/couldNotCreateObject', 'Nie mogę utworzyć obiektu "%s".');
# Scaffold Related
text::set('scaffold/returntoitems', 'Powrót od Listy Obiektów');
text::set('scaffold/createnew', 'Utworzenie Nowego Obiektu');
?>
