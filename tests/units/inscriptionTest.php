<?php

	//Same verifie aussi le type.

	use PHPUnit\Framework\TestCase;
	
    include_once __DIR__.'/../../src/PHP/inscription.php';

	class inscriptionTest extends TestCase {
		
		public function testNominalCase() {
			$err = l_verify_data('thing@other.com', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(0, count($err));
		}
		
		public function testEmailWithHTMLTag() {
		    $err = l_verify_data('<p>@</p>.<br>', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('email', $err);
			$this->assertEquals('L\'email ne peut pas contenir de code HTML.', $err['email']);
		}
		
		public function testEmailWrongFormat() {
		    $err = l_verify_data('ab.c', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('email', $err);
			$this->assertEquals('L\'adresse email ne respecte pas le bon format.', $err['email']);
		}
		
		public function testPasswordDifferentConfirmation() {
		    $err = l_verify_data('a@b.c', 'One234!7', 'One234!8', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Les mots de passe doivent être identiques.', $err['pass1']);
		}
		
		public function testPasswordWithHTMLTag() {
		    $err = l_verify_data('a@b.c', 'One234!<br>', 'One234!<br>', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('La zone Mot de passe ne peut pas contenir de code HTML.', $err['pass1']);
		}
		
		public function testPasswordTooShort() {
		    $err = l_verify_data('a@b.c', 'AbcdO3!', 'AbcdO3!', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Le mot de passe doit être constitué de 8 à 20 caractères.', $err['pass1']);
		}
		
		public function testPasswordTooLong() {
		    $err = l_verify_data('a@b.c', 'On3!!!!!!!!!!!!!!!!!!', 'On3!!!!!!!!!!!!!!!!!!', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Le mot de passe doit être constitué de 8 à 20 caractères.', $err['pass1']);
		}

		public function testPassWordMaj() {
			$err = l_verify_data('a@b.c', '1234567!', '1234567!', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Le mot de passe doit contenir au moin une majuscule, un chiffre et un caractère spécial.', $err['pass1']);
		}

		public function testPassWordDigit() {
			$err = l_verify_data('a@b.c', 'abcdefg!', 'abcdefg!', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Le mot de passe doit contenir au moin une majuscule, un chiffre et un caractère spécial.', $err['pass1']);
		}

		public function testPassWordSpecial() {
			$err = l_verify_data('a@b.c', 'abcdefg1', 'abcdefg1', 'Last', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('pass1', $err);
			$this->assertEquals('Le mot de passe doit contenir au moin une majuscule, un chiffre et un caractère spécial.', $err['pass1']);
		}
		
		public function testFirstNameWithHTMLTag() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First<br>', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('prenom', $err);
			$this->assertEquals('Le prénom ne peut pas contenir de code HTML.', $err['prenom']);
		}
		
		public function testFirstNameEmpty() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', '', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('prenom', $err);
			$this->assertEquals('Le prénom doit être renseigné.', $err['prenom']);
		}
		
		public function testFirstNameInvalidFormat() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'F1rst', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('prenom', $err);
			$this->assertEquals('Le prénom n\'est pas valide.', $err['prenom']);
		}
		
		public function testLastNameWithHTMLTag() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last<br>', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('nom', $err);
			$this->assertEquals('Le nom ne peut pas contenir de code HTML.', $err['nom']);
		}
		
		public function testLastNameEmpty() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', '', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('nom', $err);
			$this->assertEquals('Le nom doit être renseigné.', $err['nom']);
		}
		
		public function testLastNameInvalidFormat() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'L@st', 'First', 'Username', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('nom', $err);
			$this->assertEquals('Le nom n\'est pas valide.', $err['nom']);
		}
		
		public function testUsernameWithHTMLTag() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', 'Username<br>', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('username', $err);
			$this->assertEquals('Le nom d\'utilisateur ne peut pas contenir de code HTML.', $err['username']);
		}
		
		public function testUsernameEmpty() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', '', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('username', $err);
			$this->assertEquals('Le nom d\'utilisateur doit être renseigné.', $err['username']);
		}
		
		public function testUsernameInvalidFormat() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', 'User name', 27, 9, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('username', $err);
			$this->assertEquals('Le nom d\'utilisateur n\'est pas valide.', $err['username']);
		}
		
		public function testDateIncorrectDate() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 13, 2000);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('date', $err);
			$this->assertEquals('La date de naissance est incorrecte.', $err['date']);
		}
		
		public function testDateTooYoung() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 9, 2015);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('date', $err);
			$this->assertEquals('Votre date de naissance indique que vous avez moins de 12 ans.', $err['date']);
		}
		
		public function testDateTooOld() {
		    $err = l_verify_data('a@b.c', 'One234!8', 'One234!8', 'Last', 'First', 'Username', 27, 9, 1808);
			$this->assertEquals(1, count($err));
			$this->assertArrayHasKey('date', $err);
			$this->assertEquals('Votre date de naissance indique que vous avez plus de 120 ans.', $err['date']);
		}
		
		public function testSeveralErrorsOfDifferentTypes() {
		    $err = l_verify_data('ab.c', 'One234!8', 'One234!8', 'L@st', 'First', 'Username', 27, 9, 1808);
			$this->assertEquals(3, count($err));
			$this->assertArrayHasKey('date', $err);
			$this->assertArrayHasKey('email', $err);
			$this->assertArrayHasKey('nom', $err);
		}
	}
