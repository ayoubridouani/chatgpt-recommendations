<?php

    // https://www.php.net/manual/en/function.uniqid.php#120123
    function uniqidReal($lenght = 13) {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception('no cryptographically secure random function available');
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    $env = 'development';
    //$env = 'production';

    if($env == 'production'){
        $flask_url = 'https://www.example.com/';
    } else {
        $flask_url = 'http://localhost:5000';
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>GhatGPT</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            #overlay{	
                position: fixed;
                top: 0;
                z-index: 100;
                width: 100%;
                height:100%;
                display: none;
                background: rgba(0,0,0,0.6);
            }
            .cv-spinner {
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;  
            }
            .spinner {
                width: 40px;
                height: 40px;
                border: 4px #ddd solid;
                border-top: 4px #2e93e6 solid;
                border-radius: 50%;
                animation: sp-anime 0.8s infinite linear;
            }
            @keyframes sp-anime {
                100% { 
                    transform: rotate(360deg); 
                }
            }
            .is-hide{
                display:none;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center text-success mb-5"> ChatGPT Recommendations </h1>

            <form class="mb-10">
                <input id="queryId" type="hidden" value="<?php echo uniqidReal(); ?>">
                <div class="mb-3">
                    <textarea id="query" class="form-control" placeholder="Enter your query" rows="8" required autofocus></textarea>
                </div>
                <div class="mb-3">
                    <input id="email" type="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <button id="submit" type="submit" class="btn btn-primary mb-2 float-end">Submit</button>
            </form>
            <div class="mt-10">Developed by <a href="https://www.ayoubridouani.com" target="_blank"> Ayoub Ridouani </a></div>
        </div>

        <div id="overlay">
            <div class="cv-spinner">
                <span class="spinner"></span>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"> </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.querySelector("form").addEventListener("submit", function(e) {
                e.preventDefault();
                var userQueryId = $("#queryId").val();
                var userQuery = $("#query").val();
                var userEmail = $("#email").val();

                $("#overlay").fadeIn(300);
                fetch('<?php echo $flask_url; ?>', {
                    method: "POST",
                    body: JSON.stringify({ queryId: userQueryId, query: userQuery, email: userEmail }),
                    headers: { "Content-Type": "application/json" }
                })
                .then(data => {
                    $("#overlay").fadeOut(300);
                    if(data.error == 0){
                        Swal.fire({
                            title: "Good job!",
                            text: data.message,
                            icon: "success"
                        });
                        $("#queryId").prop('disabled', 'disabled');
                        $("#query").prop('disabled', 'disabled');
                        $("#email").prop('disabled', 'disabled');
                    }
                    else
                        Swal.fire({
                            title: "Oops...",
                            text: data.message,
                            icon: "error"
                        });
                })
                .catch(error => console.error("Error:", error));
            });
        </script>
    </body>
</html>