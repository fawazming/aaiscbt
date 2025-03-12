

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content page-content">
            <div class="bg-info pb-lg-64pt py-3">
                <div class="container page__container">
                    <div class="d-flex flex-wrap align-items-end justify-content-end mb-16pt">
                        <h1 class="text-white flex m-0"><?=$owner?></h1>
                    </div>
                    <?php if($quizinput == 'disabled'): ?>
                    <p class="hero__lead measure-hero-lead text-white-50">
                    You're very much welcome to <?=$owner?> Quiz Platform. <b class="text-warning"> The Quiz is currently disabled.</b> <br>Ask the administrator to reactivate it.
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="container page__container vh-50">
            <div class="d-md-flex  align-items-end justify-content-between">

            <img src="assets/images/logo.png" alt="" width="600px" class="img-fluid">

                <ol class="list-group">
                    <li class="list-group-item my-16pt mb-lg-48pt">
                        <div class="page-section">
                            <form action="quiz" method="get" class="text-center">
                                <label for="quiz code">Quiz Code</label>
                                <input <?=$quizinput?> type="text" placeholder="Enter the quiz access code" name="code" class="form-control" required>
                                <!-- <br> -->
                                <input type="submit" <?=$quizinput?> class="mt-3 btn btn-primary" value="Start Quiz">
                            </form>
                        </div>
                    </li>
                </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- // END Header Layout Content -->

        <!-- <div class="js-fix-footer2 bg-white border-top-2">
            <div class="container page__container page-section d-flex flex-column">
                <p class="text-70 brand mb-2pt">
                    <img class="brand-icon" src="assets/images/logo/black-70%402x.jpeg" width="30" alt="Luma" />
                    PHF Ogun
                </p>
                <p class="measure-lead-max text-50 small mr-8pt">
                    PHF Ogun created this quiz platform to engage its member during this
                    period.
                </p>
                <p class="text-50 small mt-n1 mb-0">
                    Copyright 2021 &copy; All rights reserved.
                </p>
            </div>
        </div>
    </div> -->
    <!-- // END Header Layout -->
