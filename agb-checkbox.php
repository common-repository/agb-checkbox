<?php
/*
Plugin Name: AGB-Checkbox
Plugin URI: http://wpmu-tutorials.de/plugins/plugin-agb-checkbox/
Description: This plugin adds to the signup page of WordPress MU a checkbox for TOS and/or a privacy policy.
Author: Dennis Morhardt
Version: 1.0
Author URI: http://www.dennismorhardt.de/
Side Wide Only: true
*/

// Einstellungen, bitte aendern!

// Text "Ich bestaetige, dass ich die AGBs gelesen und akzeptieret habe."
// Hier darf auch HTML angewendet werden, um z.B. auf die AGB-Seite zu verlinken!
$agb_text = '<a href="/agb/" title="AGB durchlesen">Ich best&auml;tige, dass ich die AGBs gelesen und akzeptieret habe.</a>';

// Meldung wenn die Checkbox nicht angeklickt wurden ist
$agb_meldung = 'Sie m&uuml;ssen die AGBs akzeptieren, damit Sie mit der Anmeldung fortfahren k&ouml;nnen!';

// Achtung: Bitte verwenden Sie keine Umlaute in $agb_text und $agb_meldung sondern wandeln Sie diese vorher in HTML-Sonderzeichen um
// Da es sonst zu Darstellungsproblemen kommen kann!
// Umwandelungstabelle: http://de.selfhtml.org/html/referenz/zeichen.htm

// Falls Sie weitere Hilfe brauchen, schreiben Sie einen Kommentar auf wpmu-tutorials.de oder nutzen Sie das WordPress MU Deutschland Forum.

// Ab hier nichts mehr aendern!

// Funktionen

// Die Funktion "anmeldung_agb_feld()" zeigt die Checkbox auf der Anmeldeseite an und gibt bei nicht Ausfuellen einen Fehler an
function anmeldung_agb_feld($errors) {
	global $agb_text;

	$fehler = $errors->get_error_message('agb');
?>
	<tr <?php echo($fehler ? 'class="error"' : '') ?>>
		<th valign="top">AGBs:</th>
		<td>
			<p><?php if($fehler) echo '<strong>' . $fehler . '</strong><br />'; ?><input type="checkbox" name="agb_zustimmung" value="1"/> <?php echo $agb_text; ?></p>
		</td>
	</tr>
<?php
}

// Die Filterfunktion sagt WordPress MU wo Fehler vorliegen
function anmeldung_agb_filter($inhalt) {
	global $agb_meldung;

	$zustimmung = (int) $_POST['agb_zustimmung'];
	
	if($zustimmung == '0' && $_POST['stage'] == 'validate-user-signup')
		$inhalt['errors']->add('agb', $agb_meldung);

	if($zustimmung == '0')
		if($_POST['stage'] == 'validate-user-signup') $inhalt['errors']->add('agb', $agb_meldung);
	
	return $inhalt;
}

// Diese Funktionen sagen WordPress MU wo welche der oberen Funktionen aufgerufen werden sollen
add_action('signup_extra_fields', 'anmeldung_agb_feld');
add_filter('wpmu_validate_user_signup', 'anmeldung_agb_filter');


?>