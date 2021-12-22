@extends('layouts.master')

@section('title') Dashboard @endsection

@section('headerCss')
<!-- headerCss -->
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
</link>
<link href="{{ URL::asset('/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
</link>
<link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />


@endsection

@section('content')

<!-- content -->
<!-- start page title -->
<!--<div class="row">

        <div class="col-sm-6">
            <div class="page-title-box">
                <h4>Investigators</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Investigators Listing</li>
                </ol>
            </div>
        </div>
        <div class="col-sm-6">

        </div>
    </div>-->
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card investogators_Details">
            <div class="card-body">

                <div class="row">
                    <div class="col-12 col-sm-4 col-xs-12">
                        <h4 class="card-title pb-4">Investigators Details</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Basic Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#documents" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-file-alt"></i></span>
                                    <span class="d-none d-sm-block">Documents</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#timeline" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-chart-line"></i></span>
                                    <span class="d-none d-sm-block">Time Line</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#history" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-clock"></i></span>
                                    <span class="d-none d-sm-block">History</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- BASIC DETAILS -->
                            <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                <h4 class="section-title mb-3 pb-2">Personal Details</h4>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_name"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Name:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-username" data-type="text" data-pk="1"
                                                    data-title="Enter username"
                                                    class="editable editable-click inline-block">Paige Turner</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_userName"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Username:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-userid" data-type="text" data-pk="2"
                                                    data-title="Enter userid"
                                                    class="editable editable-click inline-block">INI143</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_userpwd"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Password:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-userpwd" data-type="password" data-pk="3"
                                                    data-title="Enter userpwd"
                                                    class="editable editable-click inline-block">abc@12345</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_family"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Family:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input class="form-control" type="text" value="Petey Cruiser"
                                                    id="inv_email" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_idNum"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">ID
                                                Number:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input class="form-control" type="text" value="B4852DS" id="inv_idNum"
                                                    readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_dob"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Date
                                                of Birth:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input class="form-control" type="date" value="1980-09-24" id="inv_dob"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row multiple_phone">
                                            <label for="inv_phone"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Phone:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9 phone">
                                                <div class="field-wrapper">
                                                    <div class="add_fields">
                                                    <a href="#" id="inline-userphone" data-type="number" data-pk="4"
                                                        data-title="Enter userphone"
                                                        class="editable editable-click inline-block">205-629-1987</a>
                                                        <span type="button" class="remove-field"><i class="fas fa-trash"></i></span>
</div>
                                                </div>
                                                <button type="button" class="add-field"> <i class="fas fa-plus"></i> Add
                                                    field</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_mobile"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Mobile:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-usermobile" data-type="number" data-pk="5"
                                                    data-title="Enter usermobile"
                                                    class="editable editable-click inline-block">334-497-3407</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_fax"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Fax:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-userfax" data-type="number" data-pk="6"
                                                    data-title="Enter userfax"
                                                    class="editable editable-click inline-block">+44 161 999 8888</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_email"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Email:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <a href="#" id="inline-useremail" data-type="text" data-pk="7"
                                                    data-title="Enter useremail"
                                                    class="editable editable-click inline-block">14v6rpwthm5@mail.net</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_website"
                                                class="col-form-label  col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Website:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input class="form-control" id="inv_web" type="text"
                                                    value="https://www.innoactions.com" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <h4 class="section-title mb-3 pb-2">Address Details</h4>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_address_one"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Address
                                                1:</label>
                                            <div class="col-xs-10 col-sm-10 col-md-7 col-lg-7 col-xl-7">
                                                <input class="form-control" type="text" value="1865  Jessie Street"
                                                    id="inv_address_one" disabled>
                                            </div>
                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                                <button type="button" class="btn btn-primary waves-effect waves-light"
                                                    data-toggle="modal" data-target=".bs-example-modal-center"><i class="fas fa-edit"></i> Edit </i></button>
                                            </div>
                                            <div id="address_edit" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0">Edit Address</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="edit_address">
                                                                <div class="form-group">
                                                                    <label for="inv_addDesc">Address Description</label>
                                                                    <input type="text" class="form-control" id="inv_addDesc" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="inv_country">Counntry</label>
                                                                    <select class="form-control" id="inv_country">
                                                                        <option>Select</option>
                                                                        <option>United States</option>
                                                                        <option>United Kingdom</option>
                                                                        <option>India</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="inv_city">City</label>
                                                                    <input type="text" class="form-control" id="inv_city" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="inv_street">Street</label>
                                                                    <input type="text" class="form-control" id="inv_street" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="inv_number">Number</label>
                                                                    <input type="text" class="form-control" id="inv_number" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="inv_zipCode">Zip code</label>
                                                                    <input type="text" class="form-control" id="inv_zipCode" required>
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <button type="button" class="btn btn-primary btn-lg mr-3" >Cancel</button>
                                                                    <button type="button" class="btn btn-primary btn-lg">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_address_two"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">Address
                                                2:</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input class="form-control" type="text" value="Athens, OH"
                                                    id="inv_address_two" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <h4 class="section-title mb-3 pb-2">Area of Specialization </h4>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_aos"
                                                class="col-form-label col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">Area
                                                of Specialization :</label>
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                                                <a href="#" id="inline-comments" data-type="textarea" data-pk="1"
                                                    data-placeholder="Your comments here..." data-title="Enter comments"
                                                    disabled>Area 1</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTS -->
                            <div class="tab-pane p-3" id="documents" role="tabpanel">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 resp-order">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="inve_documents" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr. No</th>
                                                            <th>Document</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <th><a href="javascript:void(0);">license.jpeg</a></th>
                                                            <td>
                                                                <div class="action_btns">
                                                                    <a class="view" href="javascript:void(0);"><i
                                                                            class="fas fa-eye"></i></a>
                                                                    <a class="edit" href="javascript:void(0);"><i
                                                                            class="fas fa-edit"></i></a>
                                                                    <a class="delete" href="javascript:void(0);"><i
                                                                            class="fas fa-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <th><a href="javascript:void(0);">passport.jpeg</a></th>
                                                            <td>
                                                                <div class="action_btns">
                                                                    <a class="view" href="javascript:void(0);"><i
                                                                            class="fas fa-eye"></i></a>
                                                                    <a class="edit" href="javascript:void(0);"><i
                                                                            class="fas fa-edit"></i></a>
                                                                    <a class="delete" href="javascript:void(0);"><i
                                                                            class="fas fa-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <th><a href="javascript:void(0);">biodata.pdf</a></th>
                                                            <td>
                                                                <div class="action_btns">
                                                                    <a class="view" href="javascript:void(0);"><i
                                                                            class="fas fa-eye"></i></a>
                                                                    <a class="edit" href="javascript:void(0);"><i
                                                                            class="fas fa-edit"></i></a>
                                                                    <a class="delete" href="javascript:void(0);"><i
                                                                            class="fas fa-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <div>
                                            <form action="#" class="dropzone">
                                                <div class="fallback">
                                                    <input name="file" type="file" multiple="multiple">
                                                </div>
                                                <div class="dz-message needsclick">

                                                    <h4>Drop files here or click to upload.</h4>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="text-center mt-2 mb-3">
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-light">Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TIMELINE -->
                            <div class="tab-pane p-3" id="timeline" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <section id="cd-timeline" class="" dir="ltr">
                                            <div class="cd-timeline-block timeline-right">
                                                <div class="cd-timeline-img">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->

                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event One</h3>
                                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Iusto, optio, dolorum provident rerum aut hic
                                                        quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste
                                                        voluptatibus minus veritatis qui ut.</p>
                                                    <span class="cd-date">May 23</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->

                                            <div class="cd-timeline-block timeline-left">
                                                <div class="cd-timeline-img">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->

                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event Two</h3>
                                                    <p class="mb-4 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Iusto, optio, dolorum provident rerum aut hic
                                                        quasi placeat iure tempora laudantium</p>
                                                    <button type="button"
                                                        class="btn btn-primary btn-rounded waves-effect waves-light m-t-5">See
                                                        more detail</button>

                                                    <span class="cd-date">May 30</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->

                                            <div class="cd-timeline-block timeline-right">
                                                <div class="cd-timeline-img">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->

                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event Three</h3>
                                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Excepturi, obcaecati, quisquam id molestias
                                                        eaque error assumenda delectus. Odit, itaque, deserunt corporis
                                                        vero ipsum nisi eius odio natus ullam provident pariatur
                                                        temporibus quia eos repellat ... <a href="#"
                                                            class="text-primary">Read more</a></p>
                                                    <span class="cd-date">Jun 05</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->

                                            <div class="cd-timeline-block timeline-left">
                                                <div class="cd-timeline-img">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->

                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event Four</h3>
                                                    <p class="mb-4 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Iusto, optio, dolorum provident rerum aut.</p>
                                                    <img src="assets/images/small/img-1.jpg" alt="" class="rounded"
                                                        width="120">
                                                    <img src="assets/images/small/img-2.jpg" alt="" class="rounded"
                                                        width="120">
                                                    <span class="cd-date">Jun 14</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->

                                            <div class="cd-timeline-block timeline-right">
                                                <div class="cd-timeline-img">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->

                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event Five</h3>
                                                    <p class="mb-4 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Iusto, optio, dolorum provident rerum.</p>
                                                    <button type="button"
                                                        class="btn btn-primary btn-rounded waves-effect waves-light">See
                                                        more detail</button>
                                                    <span class="cd-date">Jun 18</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->

                                            <div class="cd-timeline-block">

                                                <div class="cd-timeline-img bg-primary d-xl-none">
                                                    <i class="mdi mdi-adjust"></i>
                                                </div>
                                                <!-- cd-timeline-img -->
                                                <div class="cd-timeline-content">
                                                    <h3>Timeline Event End</h3>
                                                    <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur
                                                        adipisicing elit. Excepturi, obcaecati, quisquam id molestias
                                                        eaque asperiores voluptatibus cupiditate error assumenda
                                                        delectus odit similique earum voluptatem doloremque dolorem
                                                        ipsam quae rerum quis. Deserunt corporis vero ipsum nisi eius
                                                        odio natus ullam provident pariatur temporibus quia eos repellat
                                                        consequuntur.</p>
                                                    <span class="cd-date">Jun 30</span>
                                                </div>
                                                <!-- cd-timeline-content -->
                                            </div>
                                            <!-- cd-timeline-block -->
                                        </section>
                                        <!-- cd-timeline -->
                                    </div>
                                    <!-- end col -->
                                </div>
                            </div>

                            <!-- HISTORY -->
                            <div class="tab-pane p-3" id="history" role="tabpanel">

                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('footerScript')
<!-- footerScript -->
<!-- Required datatable js -->
<script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/form-xeditable.init.js') }}"></script>
<script src="{{ URL::asset('/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/table-responsive.init.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#inline-userid').editable({
            type: 'text',
            pk: 2,
            name: 'userid',
            title: 'Enter userid',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-userpwd').editable({
            type: 'password',
            pk: 3,
            name: 'userpwd',
            title: 'Enter userpwd',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-userphone').editable({
            type: 'number',
            pk: 4,
            name: 'userphone',
            title: 'Enter userphone',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-usermobile').editable({
            type: 'number',
            pk: 5,
            name: 'usermobile',
            title: 'Enter usermobile',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-userfax').editable({
            type: 'number',
            pk: 6,
            name: 'userfax',
            title: 'Enter userfax',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-useremail').editable({
            type: 'text',
            pk: 7,
            name: 'useremail',
            title: 'Enter useremail',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });
        $('#inline-userweb').editable({
            type: 'text',
            pk: 8,
            name: 'userweb',
            title: 'Enter userweb',
            mode: 'inline',
            inputclass: 'form-control-sm'
        });

        $('.investogators_Details').each(function () {
            var $wrapper = $('.multiple_phone', this);
            $(".add-field", $(this)).click(function (e) {
                $('.add_fields:first-child', $wrapper).clone(true).appendTo(
                    '.field-wrapper').addClass('added').find('input').val('').focus();
            });
            $('.add_fields .remove-field', $wrapper).click(function () {
                if ($('.add_fields', $wrapper).length > 1)
                    $(this).parent('.add_fields').remove();
            });
        });

        var table = $('#datatable_investors').DataTable({
            search: true,
            lengthChange: true,
            buttons: ['excel', 'pdf'],
            // 'columnDefs': [{
            //     'targets': 0,
            //     'searchable': false,
            //     'orderable': false,
            //     'className': 'text-center',
            //     'render': function (data, type, full, meta){
            //         return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
            //     }
            // }],
            'order': [],
        });

        // Handle click on "Select all" control
        $('#datatable_investors-select-all').on('click', function () {
            // Check/uncheck all checkboxes in the table
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        // Handle click on checkbox to set state of "Select all" control
        $('#datatable_investors tbody').on('change', 'input[type="checkbox"]', function () {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#select-all').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked && ('indeterminate' in el)) {
                    // Set visual state of "Select all" control
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
        });

        $('#frm-example').on('submit', function (e) {
            var form = this;

            // Iterate over all checkboxes in the table
            table.$('input[type="checkbox"]').each(function () {
                // If checkbox doesn't exist in DOM
                if (!$.contains(document, this)) {
                    // If checkbox is checked
                    if (this.checked) {
                        // Create a hidden element
                        $(form).append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', this.name)
                            .val(this.value)
                        );
                    }
                }
            });

            // Output form data to a console
            $('datatable_investors-console').text($(form).serialize());
            console.log("Form submission", $(form).serialize());

            // Prevent actual form submission
            e.preventDefault();
        });

        table.buttons().container().appendTo('#datatable_investors_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection