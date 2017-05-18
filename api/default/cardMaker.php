<?php

class cardMaker {

    function __construct() {     
        // do nothing
    }

    function makeCard($message, $card) {
        return "
<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <title>My Card | Thank a Teacher - Appreciate the great Nigerian teachers</title>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='http://thankateacher.nigerianteachingawards.org/css/bootstrap.min.css' rel='stylesheet' type='text/css' media='all'/>
        <link href='http://thankateacher.nigerianteachingawards.org/css/icons.min.css' rel='stylesheet' type='text/css' media='all'/>
        <link href='http://thankateacher.nigerianteachingawards.org/css/theme-velvet.css' rel='stylesheet' type='text/css' media='all'/>
        <link href='http://thankateacher.nigerianteachingawards.org/css/custom.css' rel='stylesheet' type='text/css' media='all'/>
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
        <link href='http://thankateacher.nigerianteachingawards.org/css/font-questrial.css' rel='stylesheet' type='text/css'>
        <script src='https://use.fontawesome.com/a913950789.js'></script>
    </head>

    <body>
        <div class='main-container'>
      <section>
        <div class='container'>
          <div class='row card-width'>
            <div class='card-view ".$card['card_themeColor']."' style='background-image: url(http://thankateacher.nigerianteachingawards.org/admin/img/card-images/".$card['card_image'].") !important;'>
                <p><strong>Dear ".$message['msg_teacher_name']."</strong>,</p>
                <p>".$message['msg_message']."</p>
                <p>Sincerely,</p>
                <p>
                  <strong>".$message['msg_sender_name']."</strong><br>
                  ".$message['msg_class'].", ".$message['msg_school']."<br>
                  ".$message['msg_city'].", ".$message['msg_state']." 
                </p>
              </div>
              <br>
              <p class='text-center'><a href='http://thankateacher.nigerianteachingawards.org/my-card.html#/view/".$message['msg_id']."'>View Card in Browser</a></p>
          </div>
        </div>
      </section>

            <!-- Footer -->
            <footer class='footer footer-1'>
                <div class='container'>
                    <div class='row'>
                    
                        <div class='col-sm-6'>
                            <p class='sub'>
                                This card was sent using <strong>Thank A Teacher (<a href='http://thankateacher.nigerianteachingawards.org' target='_blank'>thankateacher.nigerianteachingawards.org</a>)</strong>
                            </p>
                        </div>
                    
                        <div class='col-sm-6 text-right'>
                            <a href='http://thankateacher.nigerianteachingawards.org' target='_blank'>
                                <img alt='Logo' src='http://thankateacher.nigerianteachingawards.org/img/logo.png'>
                            </a>
                        </div>  
                    </div>
                </div>
            </footer>
        </div>      
        <!-- jQuery -->
        <script src='http://thankateacher.nigerianteachingawards.org/js/jquery.min.js'></script>
        <script src='http://thankateacher.nigerianteachingawards.org/js/bootstrap.min.js'></script>
        <script src='http://thankateacher.nigerianteachingawards.org/js/smooth-scroll.min.js'></script>
        <script src='http://thankateacher.nigerianteachingawards.org/js/parallax.js'></script>
    </body>
</html>
        ";
    }

}

?>
