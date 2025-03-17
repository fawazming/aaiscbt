<!-- Content body -->
<div class="content-body">
    <!-- Content -->
    <div class="content">
        <div class="page-header d-md-flex justify-content-between">
            <div>
                <h3>Welcome, Owner</h3>
                <p class="text-muted">
                    This page gives you the tool to create individual link to compare the answer the user choose vs the correct answer.
                </p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Examinations Available</h6>
                <form action="" id="examava">
                <div class="row">
                    <div class="col-12 col-md-9">
                        <select name="exam" id="exam" class="">
                            <option value="">Pick an Exam</option>
                            <?php foreach ($quizs as $key => $quiz): ?>
                            <option value="<?=$quiz['code']?>"><?=$quiz['code']?> - <?=$quiz['title']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 form-group">
                        <input class="form-control" type="submit" value="Fetch Participants">
                    </div>
                </div>
                </form>
                <?php if (isset($participants)): ?>
                    <?php foreach ($participants as $key => $participant): ?>
                        <a href="<?=base_url('probe/'.$participant['id'])?>" target="_blank" class="button btn btn-primary"><?=$participant['user']?> - <?=$participant['score']?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- ./ Content -->

    <!-- Footer -->
    <footer class="content-footer">
        <div>
            © 2021 -
            <a href="https://rayyan.com.ng/" target="_blank">RayyanTech</a>
        </div>
    </footer>
    <!-- ./ Footer -->
</div>
<!-- ./ Content body -->
</div>
<!-- ./ Content wrapper -->
</div>
<!-- ./ Layout wrapper -->

<!-- Plugin scripts -->
<script src="<?=base_url('vendors/bundle.js')?>"></script>

<!-- App scripts -->
<script src="<?=base_url('assets/js/app.min.js')?>"></script>
</body>

</html>