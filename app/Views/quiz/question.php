<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>

    <!-- fonts --> 
    <link rel="stylesheet" href="assets/fonts/font.css">

    <!-- fontawesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="assets/css/Bootstrap/bootstrap.min.css">

    <!-- Custom Css Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/animation.css">
    
        <!--Thankyou CSS-->
        <link rel="stylesheet" href="assets/css/thankyou.css">
</head>
<body>
<div class="bg-info pb-lg-64pt py-32pt">
        <div class="container page__container">
            <div class="d-flex flex-wrap align-items-end justify-content-end mb-16pt">
                <h1 class="text-white flex m-0"><?=$title?></h1>
            </div>

            <p class="hero__lead measure-hero-lead text-white-50">
            <?=$description?>
            </p>
        </div>
    </div>
    <main class="overflow-hidden">
        <form action="quizlet"  class="show-section" id="quizlet" method="post">
            <?php
                $combo = range(0, ($qn-1));
                $cat = json_decode($questions);
                ?>
            <?php foreach ($combo as $key => $random) : ?>
            <section class="steps">
                <div class="step-inner">

                    <!-- step number -->
                    <div class="step-number">
                        Question <?=$key+1?>/<?=$qn?>
                    </div>

                    <!-- Question -->
                    <div class="quiz-question">
                        <?= ((array) $cat[$random])[0] ?>
                    </div>

                    <!-- form -->
                    <fieldset id="step<?=$key+1?>">
                        <div class="row">
                            <div class="col-6 tab-50">
                                <div class="radio-field bounce-left">
                                    <input type="radio" value="a" name="<?= $key . "que" . $random ?>" onclick="next()">
                                    <label><?= ((array) $cat[$random])[1] ?></label>
                                </div>
                            </div>
                            <div class="col-6 tab-50">
                                <div class="radio-field bounce-left delay-100">
                                <input type="radio" value="b" name="<?= $key . "que" . $random ?>" onclick="next()">
                                    <label><?= ((array) $cat[$random])[2] ?></label>
                                </div>
                            </div>
                            <div class="col-6 tab-50">
                                <div class="radio-field bounce-left delay-200">
                                    <input type="radio" value="c" name="<?= $key . "que" . $random ?>"  onclick="next()">
                                    <label><?= ((array) $cat[$random])[3] ?></label>
                                </div>
                            </div>
                            <div class="col-6 tab-50">
                                <div class="radio-field bounce-left delay-300">
                                <input type="radio" value="d" name="<?= $key . "que" . $random ?>"  onclick="next()">
                                    <label><?= ((array) $cat[$random])[4] ?></label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- Next Previous -->
                <div class="next-prev">
                    <button class="prev" type="button"><i class="fa-solid fa-arrow-left"></i>PREV QUESTION</button>
                    <?php if($key == ($qn-1)): ?>
                    <input type="submit" value="Submit" class="btn btn-primary">
                    <?php else: ?>
                    <button class="next" id="step<?=$key+2?>btn" type="button">next QUESTION<i class="fa-solid fa-arrow-right"></i></button>
                    <?php endif; ?>
                </div>
            </section>
            <?php endforeach; ?>


        </form>
    </main>

    
    <div style="position: fixed; margin-top: 45vh; margin-left: 80vw; color: #fff; background-color: #000; z-index: 100000;">
        <span class="text-monospace" id="coutdown"></span>
    </div>

        <!-- result -->
    <div class="loadingresult">
        <img src="assets/images/loading.gif" alt="loading">
    </div>

    <div class="main  thankyou-page">
        <div class="main-inner">
            <div class="logo">
                <div class="logo-icon">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <div class="logo-text">
                    Qzain.
                </div>
            </div>
            <article>
                <h1><span>Thank You</span> For Your Time!</h1>
                <span>Your submission has been received</span>
                <p>
                    Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque 
                    usu, facete detracto patrioque an per, lucilius pertinacia eu vel.
                </p>
            </article>
            
            <div class="social-media">
                <a href="#"><i class="fa-brands fa-google"></i>Google</a>
                <a href="#"><i class="fa-brands fa-apple"></i>Apple ID</a>
                <a href="#"><i class="fa-brands fa-facebook"></i>Facebook</a>
            </div>
            <div class="mb-5 back-home">
                <a href="">Back to Home</a>
            </div>
        </div>
    </div>

    <div id="error"><div class="reveal alert alert-danger" id="countdown">00:15:00</div></div>


    <!-- jQuery -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/easytimer.js"></script>
    <script>
        var cd = <?=$timer?>;
        var timer = new Timer();
        timer.start({
            countdown: true,
            startValues: {
                seconds: cd
            }
        });
        var tmr = new Timer();
        tmr.start({
            countdown: true,
            startValues: {
                seconds: cd - 10
            }
        });

        $('#countdown').html(timer.getTimeValues().toString());

        timer.addEventListener('secondsUpdated', function(e) {
            $('#countdown').html(timer.getTimeValues().toString());
        });

        timer.addEventListener('targetAchieved', function(e) {
            $('#quizlet').submit();
            $('#countdown').html('Time up!!');
        });
        tmr.addEventListener('targetAchieved', function(e) {
            $('#countdown').addClass('text-danger');
        });
    </script>

    
    <!-- bootstrap 5 -->
    <script src="assets/js/Bootstrap/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="assets/js/jQuery/jquery-3.6.3.min.js"></script>

    
    <!-- <Thankyou JS -->
        <script src="assets/js/thankyou.js"></script>
    <!-- Custom js -->
    <script src="assets/js/custom.js"></script>
    
</body>

</html>