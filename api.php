<?php 

?>

<!DOCTYPE html>
<html>
<head>
        <title>Example API</title>

        <link href='style.css' rel='stylesheet' type='text/css'/>
</head>

<body>

<div align="center">
<h1>Example API </h1>
        <a href="api.php">Submit ticket</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="search.php">Search case</a>
        <br/>
<h3>Submit new ticket</h3>


        <form method="POST" action="api.php" id="submitform">

                <table border="1" id="s_table">
				      <tr>
                        <td style="font-weight:bold">
                                        <center>* Name </center>
                                </td>
                                <td>
                                        <select name="user">
                                                <?php

                                                        $conn_users = db_connect();
                                                        $users = get_users($conn_users);
                                                                echo "<option value=\"\" selected></option>";
                                                        foreach ($users as $key => $user) {
                                                                echo "<option value=\"$user\">$user</option>";
                                                        }

                                                ?>
                                        </select>
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>* Type:</center>
                                </td>
                                <td align="left">
                                        <input type="radio" name="type" value="abuse" checked> Phishing<br>
										<input type="radio" name="type" value="abuse_tk"> Brand Abuse<br>
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>*adress</center>
                                </td>
                                <td>
                                        <input type="text" maxlength="1000" size="100" name="target">
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>* URL</center>
                                </td>
                                <td>
                                        <input type="text" maxlength="250" size="100" name="source">
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>IP</center>
                                </td>

                                <td>
                                        <input type="text" maxlength="250" size="100" name="ip">
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>* ID</center>
                                </td>
                                <td>

                                        <input type="text" maxlength="250" size="100" name="caseid">                              
                                </td>
                        </tr>
						  <tr>
                                <td style="font-weight:bold">
                                        <center>UA</center>
                                </td>
                                <td>
                                        <input type="text" maxlength="60" size="100" name="useragent">
                                </td>
                        </tr>
                        <tr>
                                <td style="font-weight:bold">
                                        <center>Comment</center>
										<center>(Proxy\vpn\etc)</center>
										
                                </td>
                                <td>
                                        <textarea type="text" maxlength="2000" size="100" name="comment" rows="3" cols="70" form="submitform"></textarea>
                                </td>
                        </tr>
						  <tr>
                                <td colspan="2" style="font-weight:bold; background-color:powderblue">
                                        <center>For Brand Abuse only:</center>
                                </td>
                        </tr>
                      	<tr>
                                <td style="font-weight:bold; background-color:powderblue">
                                        <center>* Reg number</center>
                                </td>
                                <td>
                                        <input type="text" maxlength="60" size="100" name="reg_num">
                                </td>
                        </tr>
						<tr>
                                <td style="font-weight:bold; background-color:powderblue">
                                        <center>* Reg office</center>
                                </td>
                                <td>
                                        <input type="text" maxlength="60" size="100" name="reg_office">
                                </td>
                        </tr>
                  
                        <tr>
                                <td colspan="2">
                                        <input type="submit" value="Submit ticket" style="width:100%">
                                </td>
                        </tr>
                </table>

        </form>
      
        <?php

                if (!empty($_POST) ) {
                        $act = $_POST['type'];
                        $adress = $_POST['target'];
                        $urls = $_POST['source'];
                        $ip = $_POST['ip'];
                        $id = $_POST['caseid'];
                        $comments = $_POST['comment'];
                        $name = $_POST['useragent'];
                        $user = $_POST['user'];


                        $error = array();
						if($act=='abuse_tk')
						{
							if($reg_num=='')
							{
							 $error[] = 'Error: id is empty.';	
							}
							if($reg_office=='')
							{
							 $error[] = 'Error: of is empty.';	
							}
						}
                        if ($urls == '')
                        {
                                $error[] = 'Error: URL is empty.';
                        } 
                        if ($trademark_symbol == '')
                        {
                                $error[] = 'Error: FI is empty.';
                        }
                        if ($providerCaseId == '') {
                            $error[] = 'Error: Case Id is required!';
                        }

                        if (!empty($error)) {

                            echo '<br/><br/><table border="0" id="errors" style="border:none;font-color:red;text-align:left;"><tr><td style="font-color:red;"><ul>';
                            foreach ($error as $key => $e) {
                                    echo '<li>'.$e.'</li>';
                            }
                            echo '</ul></tr></td></table>';

                        }else {

                                $json_resp = submit_ticket($act, $tk, $urls, $ip, $providerCaseId, $comments, $name, $reg_num, $reg_office);
                                if($json_resp!=""){
								$conn = db_connect();
								$createdAt = date("Y-m-d H:i:s");
								echo "<br/><h1>Your Ticket ID is: ", $json_resp ."</h1><br/>";
								$add_status = add_ticket($conn, $providerCaseId, $act, $urls, $trademark_symbol, $createdAt ,$comments, $user, $json_resp);
								}
                                
                                 

                                
                                  

                         


}}
?>
