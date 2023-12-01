<!DOCTYPE html>
<html>
    <head>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
    <?php

        $env = 'development';
        //$env = 'production';

        if($env == 'production'){
            $user = '';
            $password = '';
            $host = '';
            $database = '';
        } else {
            $user = 'root';
            $password = '';
            $host = 'localhost';
            $database = 'chatgpt_recommendations';
        }

        // Create connection
        $conn = new mysqli($host, $user, $password, $database);
        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $sql = 'SELECT id, query, email, query_id, chatbot_reply, created_at FROM info';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            echo '<table>';
                echo '<tr>
                        <th>#ID</th>
                        <th>Email</th>
                        <th>Query</th>
                        <th>Query Id</th>
                        <th>Chatbot Reply</th>
                        <th>Created at</th>
                    </tr>';
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['query']."</td>
                        <td>".$row['query_id']."</td>
                        <td>".$row['chatbot_reply']."</td>
                        <td>".$row['created_at']."</td>
                    </tr>";
            }
            echo '</table>';
        } else {
            echo '0 results';
        }

        $conn->close();
    ?>
    </body>
</html>