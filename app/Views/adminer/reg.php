<!-- Content body -->
<div class="content-body">
    <!-- Content -->
    <div class="content">
        <div class="page-header d-md-flex justify-content-between">
            <div>
                <h3>Registrations</h3>
                <p class="text-muted">

                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="card-title">...</h6>
                <div class="table-responsive">
                    <table id="recent-orders" class="table">
                        <thead>
                            <tr>
                                <!--<th>Passport</th>-->
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Programme</th>
                                <th>Juz Memorised</th>
                                <th>Parent/Guardian Name</th>
                                <th>Parent Phone</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registrant as $key => $reg) : ?>
                                <!-- Modal -->
                                <div class="modal fade" id="model<?= $reg['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Registrant Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>First Name: <span class="text-monospace"><?= $reg['fname'] ?></span></p>
                                                        <p>Last Name: <span class="text-monospace"><?= $reg['lname'] ?></span></p>
                                                        <p>Gender: <span class="text-monospace"><?= $reg['gender'] ?></span></p>
                                                        <p>Phone: <span class="text-monospace"><?= $reg['pphone'] ?></span></p>
                                                        <p>Email: <span class="text-monospace"><?= $reg['email'] ?></span></p>
                                                        <p>Address: <span class="text-monospace"><?= $reg['address'] ?></span></p>
                                                        <p>Programme: <span class="text-monospace"><?= $reg['doc'] ?></span></p>
                                                        <p>Juz Memorized: <span class="text-monospace"><?= $reg['juz'] ?></span></p>
                                                        <hr>
                                                        <hr>
                                                        <h5 class="text-center">Parent/Guardian</h5>
                                                        <p>First Name: <span class="text-monospace"><?= $reg['pfname'] ?></span></p>
                                                        <p>Last Name: <span class="text-monospace"><?= $reg['plname'] ?></span></p>
                                                        <p>Phone: <span class="text-monospace"><?= $reg['phone'] ?></span></p>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <img src="<?= $url . substr($reg['passport'], 9) ?>" class="img-fluid" alt="" loading="lazy"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td>
                                        <a data-toggle="modal" data-target="#model<?= $reg['id'] ?>" href="#!">More
                                        </a>
                                    </td>
                                    <td><?= $reg['fname'] . ' ' . $reg['lname'] ?></td>
                                    <td><?= $reg['pphone'] ?></td>
                                    <td><?= $reg['email'] ?></td>
                                    <td><?= $reg['dob'] ?></td>
                                    <td><?= $reg['gender'] ?></td>
                                    <td><?= $reg['address'] ?></td>
                                    <td><?= $reg['doc'] ?></td>
                                    <td><?= $reg['juz'] ?></td>
                                    <td><?= $reg['pfname'] . ' ' . $reg['plname'] ?></td>
                                    <td><?= $reg['phone'] ?></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="btn btn-floating" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti-more-alt"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="delete/<?=$reg['id']?>" class="dropdown-item text-danger">Delete</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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

<!-- Main scripts -->
<script src="<?=base_url('vendors/bundle.js')?>"></script>

<!-- Apex chart -->
<script src="<?=base_url('vendors/charts/apex/apexcharts.min.js')?>"></script>

<!-- FormWizard -->
<script src="<?=base_url('vendors/form-wizard/jquery.steps.min.js')?>"></script>

<!-- FormWizard -->
<script src="<?=base_url('vendors/datepicker/daterangepicker.js')?>"></script>

<!-- DataTable -->
<script src="<?=base_url('vendors/dataTable/datatables.min.js')?>"></script>

<!-- Dashboard scripts -->
<script src="<?=base_url('assets/js/dashboard.js')?>"></script>

<!-- App scripts -->
<script src="<?=base_url('assets/js/app.min.js')?>"></script>
</body>

</html>