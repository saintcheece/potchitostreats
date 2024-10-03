<?php
    session_start();
    require('../../controller/db_model.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        .no-margin {
            margin: 0;
        }
        .custom-disabled {
            background-color: #d3d3d3; 
            color: #a9a9a9;
            border: 1px solid #a9a9a9; 
            pointer-events: none; 
            cursor: not-allowed;
        }

        .custom-disabled:hover {
            background-color: #d3d3d3; 
            color: #a9a9a9; 
            border: 1px solid #a9a9a9; 
        }
        .nav-pills .nav-link {
            border-radius: 0.5rem; 
        }

        .nav-pills .nav-link.active {
            background-color: #254bc5; 
            color: white;
        }

        .nav-pills .nav-link:hover {
            background-color: #e1e1e1; 
            color: #254bc5; 
        }

    </style>
</head>
<body>
    <?php include 'layout/header.php'; ?>
    <section class="order-container">
        <div class="container">
            <div class="row">
               <!-- Navigation Bar -->
                <div class="col-md-12 mt-2">
                    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="pills-all" aria-selected="true">ALL</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-pending-tab" data-bs-toggle="pill" href="#pending" role="tab" aria-controls="pills-pending" aria-selected="false">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-processing-tab" data-bs-toggle="pill" href="#processing" role="tab" aria-controls="pills-processing" aria-selected="false">Processing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-pickup-tab" data-bs-toggle="pill" href="#pickup" role="tab" aria-controls="pills-pickup" aria-selected="false">For Pick Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-success-tab" data-bs-toggle="pill" href="#success" role="tab" aria-controls="pills-success" aria-selected="false">Success</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-cancelled-tab" data-bs-toggle="pill" href="#cancelled" role="tab" aria-controls="pills-cancelled" aria-selected="false">Cancelled</a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="col-md-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="pills-all-tab">
                            <h4 class="font-weight-bold mt-0 mb-4">All Orders</h4>
                            <!-- REMEMBER ITO YUNG WITH CUSTOM CAKE ORDERS -->
                            <div class="bg-white card mb-4 order-list shadow-sm">
                                <div class="gold-members p-4">
                                    <div class="media">
                                        <div class="media-body">
                                            <span class="float-right text-info">Pick Up on October 4, 2024<i class="icofont-check-circled text-success"></i></span>
                                            <h6 class="mb-2">Order #1-002-43X</h6>
                                            <p class="text-dark no-margin">Red Velvet Cookies - 2x</p>
                                            <p class="text-dark no-margin">Chocolate Cookies - 5x</p>
                                            <p class="text-dark no-margin">Cake Ala Mode - 1x</p>
                                            <hr>
                                            <div class="float-right">
                                                <a class="btn btn-sm btn-outline-danger" href="#" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                                    <i class="icofont-headphone-alt"></i> Cancel Order
                                                </a>
                                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="icofont-headphone-alt"></i> Pay with GCASH</a>
                                                <a class="btn btn-sm btn-primary" href="#"><i class="icofont-refresh"></i> Pay in Person</a>
                                            </div>
                                            <p class="mb-0 text-black text-primary pt-2"><span class="text-black font-weight-bold"> Pending Balance:</span> P300</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           <!-- DITO YUNG FULLY PAY TAS PWEDE PA I CANCEL -->
                           <div class="bg-white card mb-4 order-list shadow-sm">
                                <div class="gold-members p-4">
                                    <div class="media">
                                        <div class="media-body">
                                            <span class="float-right text-info">Pick Up on October 4, 2024<i class="icofont-check-circled text-success"></i></span>
                                            <h6 class="mb-2">Order #1-002-43X</h6>
                                            <p class="text-dark no-margin">Red Velvet Cookies - 2x</p>
                                            <p class="text-dark no-margin">Chocolate Cookies - 5x</p>
                                            <hr>
                                            <div class="float-right">
                                            <a class="btn btn-sm btn-outline-danger" href="#" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                                    <i class="icofont-headphone-alt"></i> Cancel Order
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DITO YUNG BAWAL NA ICANCEL -->


                            <div class="bg-white card mb-4 order-list shadow-sm">
                                <div class="gold-members p-4">
                                    <div class="media">
                                        <div class="media-body">
                                            <span class="float-right text-info">Pick Up on October 4, 2024<i class="icofont-check-circled text-success"></i></span>
                                            <h6 class="mb-2">Order #1-002-43X</h6>
                                            <p class="text-dark no-margin">Red Velvet Cookies - 2x</p>
                                            <p class="text-dark no-margin">Chocolate Cookies - 5x</p>
                                            <hr>
                                            <div class="float-right">
                                                <span class="btn btn-sm btn-outline-secondary custom-disabled" role="button" aria-disabled="true">
                                                    Cancel Order
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- DITO CANCELLED NA SIYA -->
                            <div class="bg-white card mb-4 order-list shadow-sm">
                                <div class="gold-members p-4">
                                    <div class="media">
                                        <div class="media-body">
                                            <span class="float-right text-info">Pick Up on October 4, 2024<i class="icofont-check-circled text-success"></i></span>
                                            <h6 class="mb-2">Order #1-002-43X</h6>
                                            <p class="text-dark no-margin">Red Velvet Cookies - 2x</p>
                                            <p class="text-dark no-margin">Chocolate Cookies - 5x</p>
                                            <hr>
                                            <div class="float-right">
                                            <span class="btn btn-sm btn-outline-secondary custom-disabled" role="button" aria-disabled="true">
                                                    Cancelled
                                                </span>
                                         
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pills-pending-tab">Pending orders content here...</div>
                        <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="pills-processing-tab">Processing orders content here...</div>
                        <div class="tab-pane fade" id="pickup" role="tabpanel" aria-labelledby="pills-pickup-tab">For Pick Up orders content here...</div>
                        <div class="tab-pane fade" id="success" role="tabpanel" aria-labelledby="pills-success-tab">Success orders content here...</div>
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="pills-cancelled-tab">Cancelled orders content here...</div>                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Add modal-dialog-centered class -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                You will be provided a refund, but this can't be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Confirm</button>
            </div>
        </div>
    </div>
</div>

    <script>
        document.getElementById('confirmCancel').addEventListener('click', function () {
            alert('Order cancelled and refund processed.');
            var modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
            modal.hide();
        });
    </script>
</body>
</html>
