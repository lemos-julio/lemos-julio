<?php 
/*Define Fuso-Horario*/
date_default_timezone_set('America/Sao_Paulo');
/*Bibliotecas do PHPMailer*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;*/

		/*Conexão com o banco de dados*/
		try{
			$conn = new pdo('mysql:host=localhost;dbname=impressora','root','Dplast#0*67');
			$conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		} catch (PDOExcepetion $e){
			echo 'ERROR:' . $e ->getMessage();
		}
			/*Inserindo os dados no BD*/
			if(isset($_POST['acao'])){

				$imp= $_POST['IMP_NAME'];
				$set= $_POST['SETOR'];
				$new= $_POST['NEW_NUM'];
				$old= $_POST['OLD_NUM'];
				$result=   $new - $old;
				if ($imp == 'expedicao') {
					$calc= $result*0.06;
				}else{
					$calc=$result*0.07;
				}
				$date = date('y-m-d H:i:s');
				$texto= "O numero total de paginas impressas pela {$imp} que pertence ao setor {$set} é {$result}. Portanto o valor a ser pago é: 
				  {$calc}";
				$sql = $conn->prepare('INSERT INTO impressora VALUES(null,?,?,?,?,?,?)');
				$sql->execute(array($imp,
									$set,
									$new,
									$old,
									$result,
									$date));
				echo $calc;
			}else{
				echo "Cadastro não foi concluido";
			}
			 

		/*Função para enviar e-mail*/
		$mail = new PHPMailer(true);
		
		try{
				$mail-> SMTPDebug = SMTP::DEBUG_SERVER;
				$mail-> isSMTP();
				$mail-> Host ='smtp.gmail.com';
				$mail-> SMTPAuth = true;
				$mail-> Username = 'ti@colley.com.br';
				$mail-> Password = '';
				$mail-> port = 587;

			
				/*Destinatario do e-mail*/
				$mail-> SetFrom('ti@colley.com.br');
				$mail-> addAddress('informatica1@colley.com.br');
				$mail-> isHTML(true);
				$mail-> Subject= 'Contador de Uso {$date}';
				$mail-> Body = $texto;

				if ($mail-> send()) {
						echo'Contador enviado com sucesso';
				}else {
					echo 'E-mail não enviado';
				}
			}
			catch(Exception $i){
				echo "Erro ao enviar mensagem {$mail->Errorinfo}";
			}
							
		







				
























				

							













 ?>